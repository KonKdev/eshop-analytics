<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Store;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class ShopifyService
{
    protected $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * 📦 Λήψη προϊόντων από το Shopify API
     */
    public function getProducts()
    {
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->store->access_token,
        ])->get("https://{$this->store->url}/admin/api/2025-01/products.json");

        if ($response->failed()) {
            return collect();
        }

        return collect($response->json('products') ?? []);
    }

    /**
     * 🧾 Λήψη παραγγελιών από το Shopify API
     */
    public function getOrders()
    {
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->store->access_token,
        ])->get("https://{$this->store->url}/admin/api/2025-01/orders.json");

        if ($response->failed()) {
            return collect();
        }

        return collect($response->json('orders') ?? []);
    }

    /**
     * 🔄 Συγχρονισμός προϊόντων στη βάση
     */
    public function syncProducts()
    {
        $products = $this->getProducts();

        foreach ($products as $p) {
            Product::updateOrCreate(
                ['shopify_id' => $p['id'], 'store_id' => $this->store->id],
                [
                    'name' => $p['title'],
                    'sku' => $p['variants'][0]['sku'] ?? null,
                    'price' => $p['variants'][0]['price'] ?? 0,
                    'stock_quantity' => $p['variants'][0]['inventory_quantity'] ?? 0,
                ]
            );
        }

        return $products->count();
    }

    /**
     * 🔄 Συγχρονισμός παραγγελιών στη βάση
     */
    public function syncOrders()
    {
        $orders = $this->getOrders();

        foreach ($orders as $o) {
            $order = Order::updateOrCreate(
                ['shopify_id' => $o['id']],
                [
                    'store_id' => $this->store->id,
                    'total' => $o['total_price'] ?? 0,
                    'status' => $o['financial_status'] ?? 'unknown',
                    'created_at' => $o['created_at'] ?? now(),
                    'updated_at' => $o['updated_at'] ?? now(),
                ]
            );

            // Παραγγελίες - προϊόντα
            foreach ($o['line_items'] ?? [] as $item) {
                OrderItem::updateOrCreate(
                    [
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'] ?? null,
                    ],
                    [
                        'product_name' => $item['name'] ?? 'N/A',
                        'quantity' => $item['quantity'] ?? 0,
                        'price' => $item['price'] ?? 0,
                    ]
                );
            }
        }

        return $orders->count();
    }
}
