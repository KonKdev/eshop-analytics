<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InsightsController extends Controller
{
    public function index()
    {
        // 📦 Πωλήσεις τελευταίων 30 ημερών
        $salesData = Order::where('order_date', '>=', now()->subDays(30))
            ->orderBy('order_date', 'asc')
            ->get(['order_date', 'total']);

        // 🔹 Δημιουργία labels & values
        $chartLabels = $salesData->pluck('order_date')->map(fn($d) => Carbon::parse($d)->format('Y-m-d'));
        $chartValues = $salesData->pluck('total');

        // 🔹 Default fallback μεταβλητές (ώστε να μην έχει undefined)
        $forecastDates = collect();
        $forecastValues = collect();
        $insight = '📊 Δεν υπάρχουν επαρκή δεδομένα για πρόβλεψη.';
        $topProducts = collect();

        // 🔹 Dummy forecast (αν υπάρχουν αρκετά δεδομένα)
        if ($chartValues->count() > 2) {
            $trend = $chartValues->last() - $chartValues->first();

            $forecastValues = collect([
                $chartValues->last() + ($trend * 0.2),
                $chartValues->last() + ($trend * 0.3),
                $chartValues->last() + ($trend * 0.4),
            ]);

            $forecastDates = collect([
                now()->addDays(1)->format('Y-m-d'),
                now()->addDays(2)->format('Y-m-d'),
                now()->addDays(3)->format('Y-m-d'),
            ]);

            $avg30 = $chartValues->avg();
            $avgPred = $forecastValues->avg();
            $changePct = $avg30 > 0 ? (($avgPred - $avg30) / $avg30) * 100 : 0;

            if ($changePct > 10) {
                $insight = "🚀 Προβλέπεται αύξηση πωλήσεων περίπου " . number_format($changePct, 1) . "% την επόμενη εβδομάδα.";
            } elseif ($changePct < -10) {
                $insight = "📉 Αναμένεται πτώση πωλήσεων " . abs(number_format($changePct, 1)) . "%. Ίσως χρειάζεται promo καμπάνια.";
            } else {
                $insight = "📊 Οι πωλήσεις αναμένονται σταθερές την επόμενη εβδομάδα.";
            }
        }

        // 🔹 Κορυφαία προϊόντα (προαιρετικά)
        try {
            $topProducts = Product::select('name')
                ->selectRaw('SUM(total_sold) as total_sold')
                ->groupBy('name')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $topProducts = collect();
        }

        // ✅ Επιστροφή όλων στο view
        return view('insights.index', [
            'chartLabels' => $chartLabels,
            'chartValues' => $chartValues,
            'forecastDates' => $forecastDates,
            'forecastValues' => $forecastValues,
            'insight' => $insight,
            'topProducts' => $topProducts,
        ]);
    }
}
