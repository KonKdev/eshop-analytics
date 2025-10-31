<x-app-layout>
    <div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
        <div class="card shadow-lg border-0 p-4 p-md-5" style="width: 100%; max-width: 480px;">
            <h2 class="text-center mb-4 fw-bold text-primary">🔗 Σύνδεση Καταστήματος</h2>

            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('stores.store') }}" class="needs-validation" novalidate>
                @csrf

                {{-- Επιλογή Πλατφόρμας --}}
                <div class="mb-3">
                    <label for="platform" class="form-label fw-semibold">Πλατφόρμα</label>
                    <select name="platform" id="platform" class="form-select" required>
                        <option value="woocommerce" selected>WooCommerce</option>
                        <option value="shopify">Shopify</option>
                        <option value="magento">Magento</option>
                    </select>
                </div>

                {{-- Store URL --}}
                <div class="mb-3">
                    <label for="url" class="form-label fw-semibold">Store URL</label>
                    <input type="text" name="url" id="url" class="form-control" placeholder="https://myshop.gr" required>
                    <div class="invalid-feedback">Παρακαλώ εισάγετε ένα έγκυρο URL.</div>
                </div>

                {{-- API Key / Token ανάλογα με πλατφόρμα --}}
                <div id="wooFields">
                    <div class="mb-3">
                        <label for="consumer_key" class="form-label fw-semibold">Consumer Key</label>
                        <input type="text" name="consumer_key" id="consumer_key" class="form-control">
                        <div class="invalid-feedback">Απαιτείται το Consumer Key.</div>
                    </div>

                    <div class="mb-4">
                        <label for="consumer_secret" class="form-label fw-semibold">Consumer Secret</label>
                        <input type="text" name="consumer_secret" id="consumer_secret" class="form-control">
                        <div class="invalid-feedback">Απαιτείται το Consumer Secret.</div>
                    </div>
                </div>

                <div id="shopifyFields" class="d-none">
                    <div class="mb-3">
                        <label for="access_token" class="form-label fw-semibold">Access Token</label>
                        <input type="text" name="access_token" id="access_token" class="form-control">
                        <div class="invalid-feedback">Απαιτείται το Access Token.</div>
                    </div>
                </div>

                <div id="magentoFields" class="d-none">
                    <div class="mb-3">
                        <label for="magento_token" class="form-label fw-semibold">Magento API Token</label>
                        <input type="text" name="magento_token" id="magento_token" class="form-control">
                        <div class="invalid-feedback">Απαιτείται το Magento Token.</div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold shadow-sm">
                        🚀 Σύνδεση
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- === Scripts === --}}
    <script>
        (() => {
            'use strict';
            const form = document.querySelector('.needs-validation');
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });

            // Εναλλαγή πεδίων ανάλογα με πλατφόρμα
            const platformSelect = document.getElementById('platform');
            const wooFields = document.getElementById('wooFields');
            const shopifyFields = document.getElementById('shopifyFields');
            const magentoFields = document.getElementById('magentoFields');

            platformSelect.addEventListener('change', e => {
                const val = e.target.value;
                wooFields.classList.add('d-none');
                shopifyFields.classList.add('d-none');
                magentoFields.classList.add('d-none');

                if (val === 'woocommerce') wooFields.classList.remove('d-none');
                if (val === 'shopify') shopifyFields.classList.remove('d-none');
                if (val === 'magento') magentoFields.classList.remove('d-none');
            });
        })();
    </script>
</x-app-layout>
