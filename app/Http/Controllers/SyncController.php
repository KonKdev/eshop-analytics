<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Services\ECommerceFactory;
use Illuminate\Http\Request;
use Exception;

class SyncController extends Controller
{
    // 🔹 Συγχρονισμός προϊόντων
    public function syncProducts(Store $store)
    {
        try {
            $service = ECommerceFactory::make($store);
            $service->syncProducts();

            return back()->with('success', '✅ Ο συγχρονισμός προϊόντων ολοκληρώθηκε!');
        } catch (Exception $e) {
            return back()->with('error', '⚠️ Αποτυχία συγχρονισμού προϊόντων: ' . $e->getMessage());
        }
    }

    // 🔹 Συγχρονισμός παραγγελιών
    public function syncOrders(Store $store)
    {
        try {
            $service = ECommerceFactory::make($store);
            $service->syncOrders();

            return back()->with('success', '✅ Ο συγχρονισμός παραγγελιών ολοκληρώθηκε!');
        } catch (Exception $e) {
            return back()->with('error', '⚠️ Αποτυχία συγχρονισμού παραγγελιών: ' . $e->getMessage());
        }
    }
}
