<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Store;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class MagentoService
{
    protected $store;
    protected $baseUrl;

    public function __construct(Store $store)
    {
        $this->store = $store;
        $this->baseUrl = rtrim($store->url, '/');
    }

    /**
     * 🔐 Απόκτηση access token (αν χρειάζεται)
     */
    protected function getToken()
    {
        if (!empty($this->store->access_token)) {
            return $this->store->access_token;
        }

        // Εναλλακτικά, μπορούμε να πάρουμε token από το API
        $response = Http::post("{$this->baseUrl}/rest/V1/integration/admin/token", [
            'username' => $this->store->api_user,
            'password' => $this->store->api_password,
        ]);

        return $response->json();
    }

    /**
     * 📦 Λήψη προϊόντων
     */
    public function getProducts()
    {
        $token = $this->getToken();

        $response = Http::withToken($token)
            ->get("{$this->baseUrl}/rest/V1/products?searchCriteria=");

        if ($response->failed()) {
            return collect();
        }

        $products = $response->json('items') ?? [];

        return collect($products);
    }

    /**
     * 🧾 Λήψη παραγγελιών
     */
    public function getOrders()
    {
        $token = $this->getToken();

        $response = Http::withToken($token)
            ->get("{$this->baseUrl}/rest/V1/orders?searchCriteria=");

        if ($response->failed()) {
            return collect();
        }

        $orders = $response->json('items') ?? [];

        return collect($orders);
    }

    /**
     * 🔄 Συγχρονισμός προϊόντων
     */
    public function syncProducts()
    {
        $products = $this->getProducts();

        foreach ($products as $p) {
            Product::updateOrCreate(
                ['magento_id' => $p['id'], 'store_id' => $this->store->id],
                [
                    'name' => $p['name'],
                    'sku' => $p['sku'],
                    'price' => $p['price'] ?? 0,
                    'stock_quantity' => $p['extension_attributes']['stock_item']['qty'] ?? 0,
                ]
            );
        }

        return $products->count();
    }

    /**
     * 🔄 Συγχρονισμός παραγγελιών
     */
    public function syncOrders()
    {
        $orders = $this->getOrders();

        foreach ($orders as $o) {
            $order = Order::updateOrCreate(
                ['magento_id' => $o['entity_id']],
                [
                    'store_id' => $this->store->id,
                    'total' => $o['grand_total'] ?? 0,
                    'status' => $o['status'] ?? 'unknown',
                    'created_at' => $o['created_at'] ?? now(),
                    'updated_at' => $o['updated_at'] ?? now(),
                ]
            );

            foreach ($o['items'] ?? [] as $item) {
                OrderItem::updateOrCreate(
                    [
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'] ?? null,
                    ],
                    [
                        'product_name' => $item['name'] ?? 'N/A',
                        'quantity' => $item['qty_ordered'] ?? 0,
                        'price' => $item['price'] ?? 0,
                    ]
                );
            }
        }

        return $orders->count();
    }
}
