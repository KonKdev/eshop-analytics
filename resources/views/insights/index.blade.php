<x-app-layout>
<div class="container py-4">

    {{-- 🔙 Back button --}}
    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill shadow-sm">
            ← Επιστροφή στο Dashboard
        </a>
    </div>

    {{-- 🧠 Header --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">🤖 AI Sales Insights</h2>
        <p class="text-muted">Αυτόματες αναλύσεις και προβλέψεις πωλήσεων με βάση τα δεδομένα του eShop σου</p>
    </div>

    {{-- 🔹 Insight Summary --}}
    <div class="alert alert-info shadow-sm border-0 rounded-3">
        <h5 class="fw-bold mb-1">📊 Συνολική Εικόνα</h5>
        <p class="mb-0">{{ $insight }}</p>
    </div>

    {{-- 🔹 AI Forecast Chart --}}
    <div class="card mb-5 shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="card-title mb-3 text-primary">📅 Πρόβλεψη Πωλήσεων (7 Ημερών)</h5>
            <canvas id="salesChart" height="120"></canvas>
            <p class="text-muted small mt-3">
                🔮 Οι προβλέψεις βασίζονται σε ιστορικά δεδομένα πωλήσεων.
            </p>
        </div>
    </div>

    {{-- 🔹 Top Products --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="card-title mb-3 text-success">🏆 Κορυφαία Προϊόντα</h5>

            @if($topProducts->isEmpty())
                <p class="text-muted">Δεν υπάρχουν διαθέσιμα δεδομένα προϊόντων.</p>
            @else
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Προϊόν</th>
                            <th class="text-center">Πωλήσεις</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $product)
                            <tr>
                                <td>{{ $product->name ?? '—' }}</td>
                                <td class="text-center fw-bold">{{ $product->total_sold ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

{{-- 🔹 Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels->merge($forecastDates)) !!},
        datasets: [{
            label: 'Πωλήσεις (€)',
            data: {!! json_encode($chartValues->merge($forecastValues)) !!},
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.15)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointBackgroundColor: '#0d6efd'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true },
            x: { grid: { display: false } }
        }
    }
});
</script>
</x-app-layout>
