<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-12">

            {{-- 👋 Welcome --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-black rounded-xl shadow p-8 text-center">
                <h3 class="text-2xl font-bold mb-2">👋 Καλώς ήρθες πίσω, {{ Auth::user()->name ?? 'Χρήστη' }}!</h3>
                <p class="text-sm opacity-90">Παρακολούθησε την απόδοση των καταστημάτων σου, συγχρόνισε προϊόντα και δες βασικά KPIs με μια ματιά.</p>
            </div>

            {{-- 🛒 Καταστήματα --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-8">
                <h3 class="font-bold text-2xl mb-8 text-center text-gray-900 dark:text-white border-b pb-3">
                    🛒 Τα καταστήματά σου
                </h3>

                @php($stores = auth()->user()->stores ?? collect())
                @if($stores->isEmpty())
                    <p class="text-gray-500 text-center">Δεν έχεις συνδέσει ακόμα κατάστημα.</p>
                @else
                    <div class="w-full max-w-3xl mx-auto">
                        <ul class="space-y-5">
                            @foreach($stores as $store)
                                <li class="border border-gray-200 rounded-lg p-5 flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $store->url }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $store->id }}</p>
                                    </div>
                                    <a href="#"
                                       class="bg-green-600 hover:bg-green-700 text-black font-semibold px-4 py-2 rounded-lg shadow transition">
                                       Δες παραγγελίες
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- ⚡ Ενέργειες --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-8">
                <h3 class="font-bold text-2xl mb-8 text-center text-gray-900 dark:text-white border-b pb-3">
                    ⚡ Ενέργειες
                </h3>

                <div class="flex flex-wrap justify-center gap-6 text-black font-semibold">
                    <a href="#" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition">🔄 Sync Orders</a>
                    <a href="#" class="px-6 py-3 bg-green-600 hover:bg-green-700 rounded-lg shadow transition">📦 Sync Products</a>
                    <a href="#" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg shadow transition">📊 Export CSV</a>
                    <a href="#" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 rounded-lg shadow transition">📧 Send Report</a>
                </div>
            </div>

            {{-- 📈 KPIs --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-8">
                <h3 class="font-bold text-2xl mb-8 text-center text-gray-900 dark:text-white border-b pb-3">
                    📈 Βασικά KPIs
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow text-center hover:shadow-md transition">
                        <p class="text-sm text-gray-500">Σήμερα</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">€{{ number_format($totalToday, 2) }}</p>
                        <p class="text-sm mt-1">{{ $ordersToday }} παραγγελίες</p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow text-center hover:shadow-md transition">
                        <p class="text-sm text-gray-500">Αυτή την εβδομάδα</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">€{{ number_format($totalWeek, 2) }}</p>
                        <p class="text-sm mt-1">{{ $ordersWeek }} παραγγελίες</p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow text-center hover:shadow-md transition">
                        <p class="text-sm text-gray-500">Μ.Ο. παραγγελίας</p>
                        <p class="text-3xl font-bold text-purple-600 mt-2">€{{ number_format($avgOrderValue, 2) }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
