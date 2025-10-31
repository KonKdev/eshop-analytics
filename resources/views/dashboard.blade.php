<x-app-layout>
    {{-- Πάνω ενημερωτικό banner --}}
    <div class="alert alert-info text-center rounded-0 mb-4" role="alert">
        🚀 Καλωσόρισες στο <strong>Shop Metrics Dashboard</strong> — συγχρονισμένα δεδομένα μέχρι:
        <strong>{{ now()->format('d/m/Y H:i') }}</strong>
    </div>

    <div class="container py-5">
        @php
            /** @var \Illuminate\Support\Collection $stores */
            $firstStore = $stores->first();
            $firstStoreId = $firstStore->id ?? null;
        @endphp

        {{-- 🏪 Καταστήματα --}}
        @if ($stores->isEmpty())
            <div class="card text-center shadow-lg p-5 mb-5 fade-in">
                <h3 class="fw-bold mb-3">👋 Καλωσόρισες, {{ Auth::user()->name ?? 'Χρήστη' }}!</h3>
                <p class="text-muted mb-4">
                    Δεν έχεις ακόμα συνδέσει κάποιο κατάστημα. Σύνδεσε το πρώτο σου e-shop για να ξεκινήσεις την παρακολούθηση των πωλήσεών σου.
                </p>
                <a href="{{ route('stores.create') }}" class="btn btn-primary px-4 py-2">➕ Σύνδεσε Κατάστημα</a>
            </div>
        @else
            <div class="card shadow-sm p-4 mb-5 fade-in">
                <h3 class="fw-bold mb-4 text-center">🏪 Τα καταστήματά σου</h3>
                <ul class="list-group list-group-flush">
                    @foreach ($stores as $store)
                        <li class="list-group-item d-flex justify-content-between align-items-start align-items-sm-center">
                            <div>
                                <p class="fw-semibold mb-0">{{ $store->url }}</p>
                                <small class="text-muted">ID: {{ $store->id }}</small>
                            </div>
                            <div class="d-flex flex-column flex-sm-row gap-2 mt-2 mt-sm-0">
                                <a href="{{ route('stores.orders', $store->id) }}" class="btn btn-success btn-sm">Δες παραγγελίες</a>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#deleteStoreModal{{ $store->id }}">🗑️ Διαγραφή</button>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="text-center mt-4">
                    <a href="{{ route('stores.create') }}" class="btn btn-primary px-4 py-2">➕ Προσθήκη Νέου Καταστήματος</a>
                </div>
            </div>
        @endif

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ⚡ Ενέργειες --}}
        <h4 class="mt-2 mb-3 fw-semibold">⚡ Ενέργειες</h4>
        <div class="d-flex flex-wrap gap-2 mb-4">
            @if($firstStoreId)
                <form method="POST" action="{{ route('sync.products', $firstStoreId) }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">🔄 Συγχρονισμός Προϊόντων</button>
                </form>

                <form method="POST" action="{{ route('sync.orders', $firstStoreId) }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-success">📦 Συγχρονισμός Παραγγελιών</button>
                </form>
            @else
                <div class="alert alert-warning mb-0">⚠️ Δεν υπάρχει συνδεδεμένο κατάστημα.</div>
            @endif

            <form method="GET" action="{{ route('reports.export.csv') }}">
                <button type="submit" class="btn btn-outline-secondary">📈 Εξαγωγή Αναφοράς</button>
            </form>

            <form method="POST" action="{{ route('reports.email') }}">
                @csrf
                <button type="submit" class="btn btn-outline-warning">✉️ Αποστολή Αναφοράς Email</button>
            </form>

            <a href="{{ route('insights.index') }}" class="btn btn-outline-info text-dark">📊 Insights</a>
        </div>

        {{-- 📈 KPIs --}}
        <div class="card shadow-sm p-5 mb-5 fade-in">
            <h3 class="fw-bold text-center mb-4">📈 Βασικά KPIs</h3>
            <div class="row text-center g-4">
                <div class="col-md-4">
                    <div class="p-4 border rounded bg-light">
                        <p class="text-muted mb-1">Σήμερα</p>
                        <h4 class="fw-bold text-success mb-1">€{{ number_format($totalToday ?? 0, 2) }}</h4>
                        <small>{{ $ordersToday ?? 0 }} παραγγελίες</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 border rounded bg-light">
                        <p class="text-muted mb-1">Αυτή την εβδομάδα</p>
                        <h4 class="fw-bold text-primary mb-1">€{{ number_format($totalWeek ?? 0, 2) }}</h4>
                        <small>{{ $ordersWeek ?? 0 }} παραγγελίες</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 border rounded bg-light">
                        <p class="text-muted mb-1">Μ.Ο. παραγγελίας</p>
                        <h4 class="fw-bold text-info mb-1">€{{ number_format($avgOrderValue ?? 0, 2) }}</h4>
                        <small>Από {{ $ordersWeek ?? 0 }} παραγγελίες</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- 💡 Προτεινόμενες Ενέργειες Μάρκετινγκ (Actionable) --}}
        <div class="card shadow-sm border-0 rounded-4 mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-bold mb-0 text-primary">💡 Προτεινόμενες Ενέργειες Μάρκετινγκ</h3>
                    <a href="{{ route('insights.index') }}" class="btn btn-outline-secondary btn-sm">📊 Δες Insights</a>
                </div>

                @if(!$firstStoreId)
                    <div class="alert alert-warning mb-0">Δεν υπάρχει συνδεδεμένο κατάστημα για να εκτελεστούν ενέργειες.</div>
                @else
                    <div class="row g-3">
                        {{-- 1. Χαμηλές πωλήσεις --}}
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="mb-1">📈 Καμπάνια: χαμηλές πωλήσεις</h5>
                                        <small class="text-muted">Στόχευσε τα προϊόντα κάτω από τον Μ.Ο. πωλήσεων.</small>
                                    </div>
                                    <span class="badge bg-danger align-self-start">{{ $lowSalesProducts->count() ?? 0 }} προϊόντα</span>
                                </div>
                                <div class="mt-3 d-flex gap-2">
                                    <button type="button" id="previewLowSales" class="btn btn-primary btn-sm">👁️ Προεπισκόπηση</button>
                                    <form method="POST" action="{{ route('marketing.campaign.lowSales', $firstStoreId) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary btn-sm">🚀 Εκτέλεση</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Best-sellers έκπτωση --}}
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="mb-1">🎯 Έκπτωση στα best-sellers</h5>
                                        <small class="text-muted">Γρήγορο boost στο conversion.</small>
                                    </div>
                                    <span class="badge bg-success align-self-start">{{ $bestSellersCount ?? 0 }} προϊόντα</span>
                                </div>
                                <div class="mt-3 d-flex gap-2 align-items-center">
                                    <button type="button" id="previewBestSellers" class="btn btn-success btn-sm">👁️ Προεπισκόπηση</button>
                                    <form method="POST" action="{{ route('marketing.discount.bestSellers', $firstStoreId) }}">
                                        @csrf
                                        <input type="number" name="percent" class="form-control form-control-sm w-auto d-inline-block"
                                                value="10" min="5" max="50"> %
                                        <button type="submit" class="btn btn-outline-success btn-sm">💾 Εφαρμογή</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Winback email --}}
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="mb-1">✉️ Win-back Email (30 ημέρες)</h5>
                                        <small class="text-muted">Στόχευσε ανενεργούς πελάτες.</small>
                                    </div>
                                    <span class="badge bg-primary align-self-start">{{ $dormantCustomersCount ?? 0 }} πελάτες</span>
                                </div>
                                <div class="mt-3 d-flex gap-2">
                                    <button type="button" id="previewWinback" class="btn btn-warning btn-sm">👁️ Προεπισκόπηση</button>
                                    <form method="POST" action="{{ route('marketing.email.winback', $firstStoreId) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning btn-sm">📬 Αποστολή</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- 4. Έλεγχος εικόνων --}}
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="mb-1">🔍 Review εικόνων προϊόντων</h5>
                                        <small class="text-muted">Πολλά views, χαμηλά sales → πιθανό θέμα εικόνας.</small>
                                    </div>
                                    <span class="badge bg-secondary align-self-start">{{ $suspectProductsCount ?? 0 }} προϊόντα</span>
                                </div>
                                <div class="mt-3 d-flex gap-2">
                                    <button type="button" id="previewReviewImages" class="btn btn-secondary btn-sm">👁️ Προεπισκόπηση</button>
                                    <form method="POST" action="{{ route('marketing.review.images', $firstStoreId) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-sm">🖼️ Εξαγωγή λίστας</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- 💡 Sales Assistant (λίστες) --}}
        <div class="container mt-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">💡 Προτάσεις για αύξηση πωλήσεων</h3>

                    {{-- Χαμηλές πωλήσεις --}}
                    <h5 class="fw-bold text-danger mb-3">📉 Προϊόντα με χαμηλές πωλήσεις</h5>
                    @if($lowSalesProducts->isEmpty())
                        <p class="text-muted">Δεν υπάρχουν προϊόντα με χαμηλές πωλήσεις αυτή τη στιγμή.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Προϊόν</th>
                                        <th class="text-center">Πωλήσεις</th>
                                        <th class="text-center">Ενέργεια</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($lowSalesProducts as $product)
                                    <tr>
                                        <td>{{ $product->product_name }}</td>
                                        <td class="text-center">{{ $product->total_sold }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary">🔁 Πρότεινε έκπτωση</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <p class="mt-3">📊 Μεταβολή εβδομαδιαίων πωλήσεων:
                        <strong class="{{ ($percentageChange ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($percentageChange ?? 0, 2) }}%
                        </strong>
                    </p>

                    <hr class="my-4">

                    {{-- Χαμηλό απόθεμα --}}
                    <h5 class="fw-bold text-warning mb-3">⚠️ Προϊόντα με χαμηλό απόθεμα</h5>
                    @if($lowStockProducts->isEmpty())
                        <p class="text-muted">Όλα τα προϊόντα έχουν επαρκές απόθεμα.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Προϊόν</th>
                                        <th class="text-center">Απόθεμα</th>
                                        <th class="text-center">Ενέργεια</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($lowStockProducts as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td class="text-center">{{ $product->stock }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-danger">🛒 Αναπλήρωση</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Γραφήματα --}}
        <div class="card shadow-sm p-5 mb-5 fade-in mt-5">
            <h3 class="fw-bold text-center mb-4">📊 Πωλήσεις τελευταίων 30 ημερών</h3>
            <canvas id="salesTrendChart" height="120"></canvas>
        </div>

        <div class="card shadow-sm p-5 fade-in">
            <h3 class="fw-bold text-center mb-4">📦 Παραγγελίες ανά ημέρα (demo)</h3>
            <canvas id="ordersChart" height="120"></canvas>
        </div>
    </div>

    {{-- ======= MODALS PREVIEW (4) ======= --}}
    {{-- 1: Low Sales Preview --}}
    <div class="modal fade" id="previewLowSalesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">📈 Προϊόντα με Χαμηλές Πωλήσεις</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="previewLowSalesContent">
                    <p class="text-muted text-center">Φόρτωση...</p>
                </div>
                <div class="modal-footer">
                    @if($firstStoreId)
                    <form method="POST" action="{{ route('marketing.campaign.lowSales', $firstStoreId) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">🚀 Εκτέλεση Καμπάνιας</button>
                    </form>
                    @endif
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Άκυρο</button>
                </div>
            </div>
        </div>
    </div>

    {{-- 2: Best Sellers Preview --}}
    <div class="modal fade" id="previewBestSellersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">🎯 Έκπτωση στα Best Sellers</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="previewBestSellersContent">
                    <p class="text-muted text-center">Φόρτωση...</p>
                </div>
                <div class="modal-footer">
                    @if($firstStoreId)
                    <form method="POST" action="{{ route('marketing.discount.bestSellers', $firstStoreId) }}">
                        @csrf
                        <button type="submit" class="btn btn-success">💾 Εφαρμογή Έκπτωσης</button>
                    </form>
                    @endif
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">❌ Άκυρο</button>
                </div>
            </div>
        </div>
    </div>

    {{-- 3: Winback Email Preview --}}
    <div class="modal fade" id="previewWinbackModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold">✉️ Winback Email (30 ημέρες)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="previewWinbackContent">
                    <p class="text-muted text-center">Φόρτωση...</p>
                </div>
                <div class="modal-footer">
                    @if($firstStoreId)
                    <form method="POST" action="{{ route('marketing.email.winback', $firstStoreId) }}">
                        @csrf
                        <button type="submit" class="btn btn-warning">📬 Αποστολή Email</button>
                    </form>
                    @endif
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Άκυρο</button>
                </div>
            </div>
        </div>
    </div>

    {{-- 4: Review Images Preview --}}
    <div class="modal fade" id="previewReviewImagesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title fw-bold">🔍 Έλεγχος Εικόνων Προϊόντων</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="previewReviewImagesContent">
                    <p class="text-muted text-center">Φόρτωση...</p>
                </div>
                <div class="modal-footer">
                    @if($firstStoreId)
                    <form method="POST" action="{{ route('marketing.review.images', $firstStoreId) }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary">🖼️ Εξαγωγή Λίστας</button>
                    </form>
                    @endif
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">❌ Άκυρο</button>
                </div>
            </div>
        </div>
    </div>


    {{-- ======= DELETE STORE MODALS (Dynamic) ======= --}}
    @foreach($stores as $store)
    <div class="modal fade" id="deleteStoreModal{{ $store->id }}" tabindex="-1"
            aria-labelledby="deleteStoreModalLabel{{ $store->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="deleteStoreModalLabel{{ $store->id }}">
                        Διαγραφή Καταστήματος
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Είσαι σίγουρος ότι θέλεις να διαγράψεις το <strong>{{ $store->url }}</strong>;</p>
                    <p class="text-muted small">Αυτή η ενέργεια δεν μπορεί να αναιρεθεί.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Άκυρο</button>
                    <form method="POST" action="{{ route('stores.destroy', $store->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">🗑️ Διαγραφή</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    {{-- ======= SCRIPTS ======= --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 30 ημέρες sales trend
        document.addEventListener("DOMContentLoaded", () => {
            const trendEl = document.getElementById('salesTrendChart');
            if (trendEl) {
                new Chart(trendEl.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels ?? []) !!},
                        datasets: [{
                            label: 'Πωλήσεις (€)',
                            data: {!! json_encode($chartValues ?? []) !!},
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.2)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3
                        }]
                    },
                    options: { responsive: true, plugins: { legend: { display: false } } }
                });
            }

            // Demo orders chart
            const ordersEl = document.getElementById('ordersChart');
            if (ordersEl) {
                const days = ['Δευ', 'Τρι', 'Τετ', 'Πεμ', 'Παρ', 'Σαβ', 'Κυρ'];
                const orders = [5, 8, 6, 10, 15, 9, 12];
                new Chart(ordersEl.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: days,
                        datasets: [{ label: 'Παραγγελίες', data: orders, backgroundColor: 'rgba(25,135,84,0.8)', borderRadius: 6 }]
                    },
                    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
                });
            }
        });

        // Generic loader για modals
        function loadPreview(endpoint, contentId, modalId) {
            // Έλεγχος αν υπάρχει storeId
            const firstStoreId = {{ $firstStoreId ?? 'null' }};
            if (!firstStoreId) return;

            const modal = new bootstrap.Modal(document.getElementById(modalId));
            const content = document.getElementById(contentId);
            content.innerHTML = '<p class="text-muted text-center">Φόρτωση...</p>';
            modal.show();

            fetch(endpoint)
                .then(res => res.json())
                .then(data => {
                    if (!data || data.length === 0) {
                        content.innerHTML = '<p class="text-muted text-center">Δεν βρέθηκαν δεδομένα.</p>';
                        return;
                    }
                    // Αν γυρίζει array από objects -> φτιάξε generic table
                    let html = '<div class="table-responsive"><table class="table table-hover align-middle"><thead class="table-light"><tr>';
                    const cols = Object.keys(data[0]);
                    cols.forEach(c => html += `<th class="text-nowrap">${c}</th>`);
                    html += '</tr></thead><tbody>';
                    data.forEach(row => {
                        html += '<tr>' + cols.map(c => `<td>${row[c] ?? ''}</td>`).join('') + '</tr>';
                    });
                    html += '</tbody></table></div>';
                    content.innerHTML = html;
                })
                .catch(() => content.innerHTML = '<p class="text-danger text-center">Σφάλμα κατά τη φόρτωση.</p>');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const firstStoreId = {{ $firstStoreId ?? 'null' }};

            // Bind κουμπιών
            document.getElementById('previewLowSales')?.addEventListener('click', () =>
                loadPreview(firstStoreId ? `{{ route('marketing.preview.lowSales', $firstStoreId) }}` : '#',
                    'previewLowSalesContent', 'previewLowSalesModal'));

            document.getElementById('previewBestSellers')?.addEventListener('click', () =>
                loadPreview(firstStoreId ? `{{ route('marketing.preview.bestSellers', $firstStoreId) }}` : '#',
                    'previewBestSellersContent', 'previewBestSellersModal'));

            document.getElementById('previewWinback')?.addEventListener('click', () =>
                loadPreview(firstStoreId ? `{{ route('marketing.preview.winback', $firstStoreId) }}` : '#',
                    'previewWinbackContent', 'previewWinbackModal'));

            document.getElementById('previewReviewImages')?.addEventListener('click', () =>
                loadPreview(firstStoreId ? `{{ route('marketing.preview.reviewImages', $firstStoreId) }}` : '#',
                    'previewReviewImagesContent', 'previewReviewImagesModal'));
        });
    </script>

    {{-- Μικρό animation (μπορεί να μεταφερθεί στο custom CSS) --}}
 <style>
    /* ... άλλα στυλ ... */

    /* Ρύθμιση μεγέθους Logo */
    /* #site-logo {
        max-height: 0px; 
        width: auto;
    } */

    /* Μείωση μεγέθους Logo σε οθόνες έως 768px (Mobile/Tablet) */
    /* @media (max-width: 767.98px) {
        #site-logo {
            max-height: 70px; /* Μικρότερο μέγεθος logo στο Mobile */
        }
    } */

    /* ... animation styles ... */
    .fade-in{opacity:0;transform:translateY(20px);animation:fadeInUp .6s ease forwards}
    @keyframes fadeInUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
</style>

</x-app-layout>