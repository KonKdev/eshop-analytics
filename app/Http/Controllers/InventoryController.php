<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use App\Services\WooCommerceService;


class InventoryController extends Controller
{
    //



     public function index()
        {
            $products = Product::where('store_id', auth()->user()->store_id)->get();
            return view('inventory.index', compact('products'));
        }

    public function sync()
        {
            $store = auth()->user()->store;
            app(WooCommerceService::class)->syncProducts($store);
            return redirect()->route('inventory.index')->with('success', 'Products synced!');
        }
}
