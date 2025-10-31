<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold text-dark fs-4 mb-4">🔮 Predictive Insights</h2>
    </x-slot>

    <div class="container py-5">

        <div class="card shadow-sm p-4 mb-4">
            <h4 class="fw-semibold mb-3">🧠 AI Εκτίμηση Πωλήσεων</h4>
            <p class="lead">{{ $insight }}</p>
        </div>

        <div class="card shadow-sm p-5 mb-4">
            <h4 class="fw-semibold mb-4">📈 Πραγματικές vs Προβλεπόμενες Πωλήσεις</h4>
            <canvas id="forecastChart" height="120"></canvas>
        </div>

        <a href="{{ route('dashboard') }}" class="btn btn-secondary">⬅ Επιστροφή στο Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('forecastChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_merge($labels, array_keys($predicted))) !!},
                datasets: [
                    {
                        label: 'Πραγματικές Πωλήσεις',
                        data: {!! json_encode(array_values($values)) !!},
                        borderColor: '#0d6efd',
                        tension: 0.4,
                        fill: false
                    },
                    {
                        label: 'Πρόβλεψη',
                        data: [
                            ...Array({{ count($values) }}).fill(null),
                            ...{!! json_encode(array_values($predicted)) !!}
                        ],
                        borderColor: '#ffc107',
                        borderDash: [5,5],
                        tension: 0.4,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</x-app-layout>
