<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class MarketingPreviewController extends Controller
{
    /**
     * Προϊόντα με χαμηλές πωλήσεις
     */
    public function previewLowSales(Store $store)
    {
        return response()->json([
            ['name' => 'Red Sneakers', 'total_sold' => 2, 'price' => '€79.90'],
            ['name' => 'Blue Hoodie', 'total_sold' => 3, 'price' => '€49.90'],
            ['name' => 'Green Cap', 'total_sold' => 1, 'price' => '€19.90'],
        ]);
    }

    /**
     * Best Sellers για έκπτωση
     */
    public function previewBestSellers(Store $store)
    {
        return response()->json([
            ['name' => 'Black Leather Jacket', 'total_sold' => 120, 'price' => '€129.90'],
            ['name' => 'White Sneakers', 'total_sold' => 98, 'price' => '€89.00'],
            ['name' => 'Denim Jeans', 'total_sold' => 85, 'price' => '€59.90'],
        ]);
    }

    /**
     * Πελάτες για Winback Email
     */
    public function previewWinback(Store $store)
    {
        return response()->json([
            ['customer' => 'Maria Papadopoulou', 'last_order' => '2025-08-12', 'email' => 'maria@example.com'],
            ['customer' => 'Nikos Georgiou', 'last_order' => '2025-07-30', 'email' => 'nikos@example.com'],
            ['customer' => 'Eleni Kosta', 'last_order' => '2025-07-05', 'email' => 'eleni@example.com'],
        ]);
    }

    /**
     * Προϊόντα με ύποπτες εικόνες (πολλά views / λίγες πωλήσεις)
     */
    public function previewReviewImages(Store $store)
    {
        return response()->json([
            ['name' => 'Yellow T-Shirt', 'views' => 540, 'sales' => 5],
            ['name' => 'Grey Backpack', 'views' => 420, 'sales' => 4],
            ['name' => 'White Hat', 'views' => 390, 'sales' => 2],
        ]);
    }
}
