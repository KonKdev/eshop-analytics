<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;

class ReportController extends Controller
{
    public function index()
    {
        // Συνολικές παραγγελίες
        $totalOrders = Order::count();

        // Συνολικά έσοδα
        $totalRevenue = Order::sum('total');

        // Σήμερα
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();

        // Ημερήσια πωλήσεις (για γράφημα)
        $dailySales = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Top 5 products by sales quantity
        $topProducts = DB::table('order_items')
            ->select('product_name', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        
        $topProducts = OrderItem::select('product_name', \DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('reports.index', [
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'todayOrders' => $todayOrders,
            'dailySales' => $dailySales,
            'topProducts' => $topProducts,
            
        ]);
    }
}
