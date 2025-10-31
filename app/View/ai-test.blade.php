<x-app-layout>
<div class="container py-5">
    <h2 class="fw-bold mb-4">🤖 AI Sales Forecast (Test Page)</h2>

    @isset($error)
        <div class="alert alert-danger">{{ $error }}</div>
    @else
        <div class="card shadow-sm p-4">
            <h5 class="mb-3">📅 Προβλέψεις για τις επόμενες ημέρες</h5>

            <ul class="list-group list-group-flush">
                @foreach($forecast['forecast'] as $item)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $item['date'] }}</span>
                        <strong>€{{ $item['prediction'] }}</strong>
                    </li>
                @endforeach
            </ul>
        </div>
    @endisset

    <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">← Επιστροφή</a>
    </div>
</div>
</x-app-layout>
