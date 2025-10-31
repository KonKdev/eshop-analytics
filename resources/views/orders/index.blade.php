<x-app-layout>
    <x-slot name="header">

    <div class="mb-4">
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
        ← Επιστροφή στο Dashboard
    </a>
</div>

        <h2 class="font-semibold text-xl text-gray-800">
            Παραγγελίες Καταστήματος
        </h2>
    </x-slot>

    <div class="p-6">
        @if (count($orders) === 0)
            <p class="text-gray-600">Δεν υπάρχουν παραγγελίες.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 bg-white rounded-lg shadow">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Order ID</th>
                            <th class="px-4 py-2 text-left">Πελάτης</th>
                            <th class="px-4 py-2 text-left">Προϊόντα</th>
                            <th class="px-4 py-2 text-left">Σύνολο</th>
                            <th class="px-4 py-2 text-left">Κατάσταση</th>
                            <th class="px-4 py-2 text-left">Ημερομηνία</th>
                        </tr>
                    </thead>
                    <tbody>
                      @forelse($orders as $order)
            <tr>
                <td class="border px-2 py-1">{{ $order->order_id }}</td>
                <td class="border px-2 py-1">{{ $order->customer_name }}</td>
                <td class="border px-2 py-1">{{ $order->total }} {{ $order->currency }}</td>
                <td class="border px-2 py-1">{{ $order->status }}</td>
                <td class="border px-2 py-1">{{ $order->order_date }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center p-3">Δεν υπάρχουν παραγγελίες</td>
            </tr>
                @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
