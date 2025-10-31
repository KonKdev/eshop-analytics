<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\Order;
use Carbon\Carbon;

class ProductInsightsController extends Controller
{
    public function index()
    {
        // Κορυφαία προϊόντα
        $topProducts = OrderItem::selectRaw('product_name, SUM(quantity) as total_sold, SUM(quantity * price) as total_revenue')
            ->groupBy('product_name')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // Εβδομαδιαίες πωλήσεις
        $weeklySales = Order::selectRaw('DATE(created_at) as date, SUM(total) as total_sales')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Μεταβλητές για το γράφημα προϊόντων
        $chartLabels = $topProducts->pluck('product_name');
        $chartData   = $topProducts->pluck('total_revenue');
        $weeklyLabels = $weeklySales->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d/m'));
        $weeklyData   = $weeklySales->pluck('total_sales');


        return view('insights.index', compact(
            'topProducts',
            'weeklySales',
            'chartLabels',
            'chartData',
            'weeklyLabels',
            'weeklyData'
        ));
    }
}
