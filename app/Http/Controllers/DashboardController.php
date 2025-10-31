<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\Notification;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\WooCommerceService;

class DashboardController extends Controller
{
    /**
     * Εξαγωγή όλων των παραγγελιών σε CSV
     */
    public function exportCsv()
    {
        $orders = Order::all(['id', 'store_id', 'total', 'created_at']);

        $csv = "Order ID,Store ID,Total,Created At\n";
        foreach ($orders as $order) {
            $csv .= "{$order->id},{$order->store_id},{$order->total},{$order->created_at}\n";
        }

        $filename = 'orders_export_' . now()->format('Ymd_His') . '.csv';

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    /**
     * Αποστολή ημερήσιας αναφοράς στο email του χρήστη
     */
    public function sendReportEmail()
    {
        $user = auth()->user();

        $reportSummary = [
            'totalToday' => 120.50,
            'ordersToday' => 3,
            'avgOrderValue' => 40.17,
        ];

        Mail::raw(
            "📊 Αναφορά Ημέρας:\nΣύνολο: €{$reportSummary['totalToday']}\nΠαραγγελίες: {$reportSummary['ordersToday']}\nΜ.Ο.: €{$reportSummary['avgOrderValue']}",
            function ($message) use ($user) {
                $message->to($user->email)->subject('📊 Αναφορά Πωλήσεων Ημέρας');
            }
        );

        return back()->with('success', '✉️ Η αναφορά στάλθηκε στο email σου!');
    }

    /**
     * Κεντρικός πίνακας ελέγχου
     */
    public function index()
    {
        $user = auth()->user();
        $stores = $user->stores ?? collect();

        if ($stores->isEmpty()) {
            return view('dashboard', compact('stores'));
        }

        $store = $stores->first();
        $woo = new WooCommerceService($store);

        // 🔹 Φέρνουμε παραγγελίες από WooCommerce API
        $ordersResponse = $woo->getOrders();

        // Αν δεν υπάρχουν δεδομένα
        if (!$ordersResponse || empty($ordersResponse->orders)) {
            return view('dashboard', [
                'stores' => $stores,
                'totalToday' => 0,
                'ordersToday' => 0,
                'totalWeek' => 0,
                'ordersWeek' => 0,
                'avgOrderValue' => 0,
                'percentageChange' => 0,
                'chartLabels' => [],
                'chartValues' => [],
                'lowSalesProducts' => collect(),
                'lowStockProducts' => collect(),
            ]);
        }

        // Μετατρέπουμε τα orders σε Collection
        $orders = collect($ordersResponse->orders);

        // --------------------------------
        // 📊 Υπολογισμοί KPIs
        // --------------------------------
        $totalSales = $orders->sum(fn($o) => (float)($o->total ?? 0));
        $totalOrders = $orders->count();
        $avgOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Πωλήσεις σήμερα
        $today = now()->toDateString();
        $ordersToday = $orders->filter(fn($o) => isset($o->date_created) && str_starts_with($o->date_created, $today));
        $totalToday = $ordersToday->sum(fn($o) => (float)($o->total ?? 0));

        // Πωλήσεις εβδομάδας (με βάση date_created)
        $startOfWeek = now()->startOfWeek()->toDateString();
        $endOfWeek = now()->endOfWeek()->toDateString();
        $ordersWeek = $orders->filter(function ($o) use ($startOfWeek, $endOfWeek) {
            $date = substr($o->date_created ?? '', 0, 10);
            return $date >= $startOfWeek && $date <= $endOfWeek;
        });
        $totalWeek = $ordersWeek->sum(fn($o) => (float)($o->total ?? 0));

        // Πωλήσεις της προηγούμενης εβδομάδας
        $lastWeekStart = now()->subWeek()->startOfWeek()->toDateString();
        $lastWeekEnd = now()->subWeek()->endOfWeek()->toDateString();
        $ordersLastWeek = $orders->filter(function ($o) use ($lastWeekStart, $lastWeekEnd) {
            $date = substr($o->date_created ?? '', 0, 10);
            return $date >= $lastWeekStart && $date <= $lastWeekEnd;
        });
        $totalLastWeek = $ordersLastWeek->sum(fn($o) => (float)($o->total ?? 0));

        $percentageChange = $totalLastWeek > 0
            ? (($totalWeek - $totalLastWeek) / $totalLastWeek) * 100
            : 0;

        // --------------------------------
        // 📈 Γραφήματα - 30 τελευταίες ημέρες
        // --------------------------------
        $salesData = $orders
            ->groupBy(fn($o) => substr($o->date_created ?? '', 0, 10))
            ->map(fn($group) => $group->sum(fn($o) => (float)($o->total ?? 0)))
            ->sortKeys();

        $chartLabels = $salesData->keys();
        $chartValues = $salesData->values();

        // --------------------------------
        // 💡 Προτάσεις (Demo)
        // --------------------------------
        $lowSalesProducts = collect([
            (object)['product_name' => 'Μπλούζα Γυμναστηρίου', 'total_sold' => 2],
            (object)['product_name' => 'Αθλητικό Σορτσάκι', 'total_sold' => 3],
        ]);

        $lowStockProducts = collect([
            (object)['name' => 'Κάλτσες Προπόνησης', 'stock' => 4],
            (object)['name' => 'Παντελόνι Running', 'stock' => 2],
        ]);

        // --------------------------------
        // 🔔 Ειδοποιήσεις (Παράδειγμα)
        // --------------------------------
        if ($ordersToday->count() > 10) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'milestone',
                'title' => '🎉 Επίτευξη στόχου!',
                'message' => 'Έχεις πάνω από 10 παραγγελίες σήμερα!',
            ]);
        }

        // --------------------------------
        // Επιστροφή στο view
        // --------------------------------
        return view('dashboard', compact(
            'stores',
            'totalToday',
            'ordersToday',
            'totalWeek',
            'ordersWeek',
            'avgOrderValue',
            'percentageChange',
            'chartLabels',
            'chartValues',
            'lowSalesProducts',
            'lowStockProducts'
        ));
    }
}
