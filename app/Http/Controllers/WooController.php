<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Automattic\WooCommerce\Client;
use App\Models\Store;

class WooController extends Controller
{
    public function fetchOrders(Store $store)
    {
        try {
            $woocommerce = new Client(
                $store->url,
                $store->consumer_key,
                $store->consumer_secret,
                [
                    'version' => 'wc/v3',
                    'verify_ssl' => false,
                ]
            );

            $orders = $woocommerce->get('orders');

            return response()->json([
                'status' => 'success',
                'count' => count($orders),
                'orders' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
