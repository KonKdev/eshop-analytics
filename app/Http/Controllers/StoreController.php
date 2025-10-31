<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\ECommerceFactory;
use Exception;

class StoreController extends Controller
{
    // 📋 Εμφάνιση λίστας καταστημάτων
    public function index()
    {
        $stores = Store::where('user_id', auth()->id())->get();
        return view('dashboard', compact('stores'));
    }

    // 🗑️ Διαγραφή καταστήματος
    public function destroy(Store $store)
    {
        if ($store->user_id !== auth()->id()) {
            abort(403, 'Μη εξουσιοδοτημένη ενέργεια.');
        }

        try {
            $store->delete();
            return redirect()->route('dashboard')->with('success', '🗑️ Το κατάστημα διαγράφηκε με επιτυχία!');
        } catch (Exception $e) {
            return redirect()->route('dashboard')->with('error', '❌ Αποτυχία διαγραφής: ' . $e->getMessage());
        }
    }

    // ➕ Δημιουργία νέου καταστήματος
    public function create()
    {
        return view('stores.create');
    }

    // 💾 Αποθήκευση καταστήματος
    public function store(Request $request)
    {
        $rules = [
            'url' => 'required|url',
            'platform' => 'required|string|in:woocommerce,shopify,magento',
        ];

        // Διαφορετικά validations ανά πλατφόρμα
        if ($request->platform === 'woocommerce') {
            $rules['consumer_key'] = 'required|string';
            $rules['consumer_secret'] = 'required|string';
        } elseif ($request->platform === 'shopify') {
            $rules['access_token'] = 'required|string';
        } elseif ($request->platform === 'magento') {
            $rules['magento_token'] = 'required|string';
        }

        $request->validate($rules);

        // Δημιουργία Store
        $store = Store::create([
            'user_id' => auth()->id(),
            'url' => $request->url,
            'platform' => $request->platform,
            'consumer_key' => $request->consumer_key ?? null,
            'consumer_secret' => $request->consumer_secret ?? null,
            'access_token' => $request->access_token ?? null,
            'magento_token' => $request->magento_token ?? null,
        ]);

        // Έλεγχος σύνδεσης με API
        try {
            $service = ECommerceFactory::make($store);
            $orders = $service->getOrders();

            if ($orders && count($orders) > 0) {
                return redirect()->route('dashboard')
                    ->with('success', '✅ Το κατάστημα συνδέθηκε επιτυχώς!');
            } else {
                return redirect()->route('dashboard')
                    ->with('warning', '⚠️ Συνδέθηκε, αλλά δεν βρέθηκαν παραγγελίες.');
            }
        } catch (Exception $e) {
            return redirect()->route('stores.create')
                ->with('error', '⚠️ Η σύνδεση απέτυχε: ' . $e->getMessage());
        }
    }

    // 📦 Προβολή παραγγελιών
    public function orders(Store $store)
    {
        try {
            $service = ECommerceFactory::make($store);
            $orders = $service->getOrders();

            foreach ($orders as $o) {
                Order::updateOrCreate(
                    [
                        'store_id' => $store->id,
                        'order_id' => $o->id ?? $o['id'] ?? null,
                    ],
                    [
                        'status' => $o->status ?? $o['status'] ?? 'unknown',
                        'total' => $o->total ?? $o['total'] ?? 0,
                        'currency' => $o->currency ?? $o['currency'] ?? 'EUR',
                        'customer_name' => ($o->billing->first_name ?? $o['customer_name'] ?? '') . ' ' .
                                           ($o->billing->last_name ?? ''),
                        'order_date' => $o->date_created ?? now(),
                    ]
                );
            }

            $dbOrders = $store->orders()->latest()->get();

            return view('orders.index', [
                'store' => $store,
                'orders' => $dbOrders
            ]);

        } catch (Exception $e) {
            return back()->with('error', '❌ Αποτυχία λήψης παραγγελιών: ' . $e->getMessage());
        }
    }
}
