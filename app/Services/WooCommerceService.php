<?php

namespace App\Services;

use Automattic\WooCommerce\Client;
use App\Models\{Store, Product, Order, OrderItem};
use App\Services\Contracts\ECommerceServiceInterface;

class WooCommerceService implements ECommerceServiceInterface
{
    protected $store;
    protected $client;

    public function __construct(Store $store)
    {
        $this->store = $store;

        $this->client = new Client(
            $store->url,
            $store->consumer_key,
            $store->consumer_secret,
            ['version' => 'wc/v3']
        );
    }

    // 🔹 Επιστροφή όλων των παραγγελιών
    public function getOrders()
    {
        return collect($this->client->get('orders'));
    }

    // 🔹 Επιστροφή όλων των προϊόντων
    public function getProducts()
    {
        return collect($this->client->get('products'));
    }

    // 🔹 (Optional) Επιστροφή πελατών
    public function getCustomers()
    {
        return collect($this->client->get('customers'));
    }

    // 🔹 Συγχρονισμός προϊόντων
    public function syncProducts()
    {
        $products = $this->getProducts();

        foreach ($products as $p) {
            Product::updateOrCreate(
                [
                    'woo_id' => $p['id'] ?? null,
                    'store_id' => $this->store->id
                ],
                [
                    'name' => $p['name'] ?? '—',
                    'sku' => $p['sku'] ?? null,
                    'price' => $p['price'] ?? 0,
                    'stock_quantity' => $p['stock_quantity'] ?? 0,
                    'stock_status' => $p['stock_status'] ?? 'unknown',
                ]
            );
        }

        return $products->count();
    }

    // 🔹 Συγχρονισμός παραγγελιών
    public function syncOrders()
    {
        $orders = $this->getOrders();

        foreach ($orders as $wooOrder) {
            $order = Order::updateOrCreate(
                ['woo_id' => $wooOrder['id']],
                [
                    'store_id'   => $this->store->id,
                    'total'      => $wooOrder['total'],
                    'status'     => $wooOrder['status'],
                    'created_at' => $wooOrder['date_created'] ?? now(),
                    'updated_at' => $wooOrder['date_modified'] ?? now(),
                ]
            );

            if (!empty($wooOrder['line_items'])) {
                foreach ($wooOrder['line_items'] as $item) {
                    OrderItem::updateOrCreate(
                        [
                            'order_id'   => $order->id,
                            'product_id' => $item['product_id']
                        ],
                        [
                            'product_name' => $item['name'] ?? '',
                            'quantity'     => $item['quantity'] ?? 0,
                            'price'        => $item['price'] ?? 0,
                        ]
                    );
                }
            }
        }

        return $orders->count();
    }
}
