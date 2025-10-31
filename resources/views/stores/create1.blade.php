<x-app-layout>
    <div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
        <div class="card shadow-lg border-0 p-4 p-md-5" style="width: 100%; max-width: 480px;">
            <h2 class="text-center mb-4 fw-bold text-primary">🔗 Σύνδεση WooCommerce Store</h2>

            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('stores.store') }}" class="needs-validation" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="url" class="form-label fw-semibold">Store URL</label>
                    <input type="text" name="url" id="url" class="form-control" placeholder="https://myshop.gr" required>
                    <div class="invalid-feedback">Παρακαλώ εισάγετε ένα έγκυρο URL.</div>
                </div>

                <div class="mb-3">
                    <label for="consumer_key" class="form-label fw-semibold">Consumer Key</label>
                    <input type="text" name="consumer_key" id="consumer_key" class="form-control" required>
                    <div class="invalid-feedback">Απαιτείται το Consumer Key.</div>
                </div>

                <div class="mb-4">
                    <label for="consumer_secret" class="form-label fw-semibold">Consumer Secret</label>
                    <input type="text" name="consumer_secret" id="consumer_secret" class="form-control" required>
                    <div class="invalid-feedback">Απαιτείται το Consumer Secret.</div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold shadow-sm">
                        🚀 Σύνδεση
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Bootstrap Validation Script --}}
    <script>
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</x-app-layout>
