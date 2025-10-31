<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Support\Facades\Log;

class MarketingController extends Controller
{
    // 🔹 Προεπισκόπηση προϊόντων με χαμηλές πωλήσεις (για modal)
    public function previewLowSales(Store $store)
    {
        // Αν δεν υπάρχει στήλη total_sold → κάνουμε προσωρινή προσομοίωση
        $products = Product::where('store_id', $store->id)
            ->inRandomOrder() // προσωρινά για δοκιμή
            ->take(10)
            ->get(['id', 'name']); // μόνο ό,τι χρειαζόμαστε

        // Προσθέτουμε fake πωλήσεις για εμφάνιση στο modal
        $products = $products->map(function ($p) {
            $p->total_sold = rand(1, 20);
            return $p;
        });

        return response()->json($products);
    }

    // 🔹 Καμπάνια για προϊόντα με χαμηλές πωλήσεις
    public function campaignLowSales(Store $store)
    {
        $lowSales = Product::where('store_id', $store->id)
            ->inRandomOrder() // δεν έχουμε total_sold προς το παρόν
            ->take(10)
            ->get();

        foreach ($lowSales as $p) {
            Log::info("📈 Καμπάνια - Προϊόν με χαμηλές πωλήσεις: {$p->name}");
        }

        return back()->with('success', '📈 Δημιουργήθηκε καμπάνια για προϊόντα με χαμηλές πωλήσεις!');
    }

    // 🔹 Έκπτωση στα best-sellers
    public function discountBestSellers(Request $request, Store $store)
    {
        $percent = $request->input('percent', 10);

        $bestSellers = Product::where('store_id', $store->id)
            ->inRandomOrder()
            ->take(10)
            ->get();

        foreach ($bestSellers as $p) {
            $discounted = $p->price - ($p->price * $percent / 100);
            $p->update(['price' => $discounted]);
        }

        return back()->with('success', "🎯 Εφαρμόστηκε έκπτωση {$percent}% στα best-sellers!");
    }

    // 🔹 Winback Email
    public function emailWinback(Store $store)
    {
        $thirtyDaysAgo = now()->subDays(30);

        $customers = Order::where('store_id', $store->id)
            ->where('order_date', '<', $thirtyDaysAgo)
            ->select('customer_name')
            ->distinct()
            ->get();

        return back()->with('success', "✉️ Ετοιμάστηκαν email σε {$customers->count()} ανενεργούς πελάτες!");
    }

    // 🔹 Review εικόνων
    public function reviewImages(Store $store)
    {
        $suspects = Product::where('store_id', $store->id)
            ->inRandomOrder()
            ->take(5)
            ->get(['id', 'name']);

        return response()->json($suspects);
    }
}
