<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/store.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="ShopMetrics" id="site-logo" class="me-2">
            {{-- <span class="d-none d-lg-inline fw-bold text-primary">ShopMetrics</span> --}}
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                
                {{-- Τα Navigation Links --}}
                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">🏠 Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('orders.index', auth()->user()->stores->first()->id ?? 1) }}">📦 Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('insights.index') }}">📊 Insights</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('stores.create') }}">🛒 Stores</a></li>


                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-semibold text-secondary" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        👤 {{ Auth::user()->name ?? 'Χρήστης' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">⚙️ Προφίλ</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">🚪 Αποσύνδεση</button>
                            </form>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown position-relative">
                    <a class="nav-link" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell-fill fs-4 text-secondary"></i>
                        <span id="notificationCount"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm"
                                style="font-size: 0.75rem; min-width: 22px; height: 22px; line-height: 14px; display:none;">
                            0
                        </span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3"
                        aria-labelledby="notificationDropdown"
                        style="width: 340px; max-height: 420px; overflow-y: auto;">
                        <li>
                            <h6 class="dropdown-header fw-semibold text-center bg-light sticky-top border-bottom">
                                🔔 Ειδοποιήσεις
                            </h6>
                        </li>
                        <div id="notificationList" class="p-3 small text-muted text-center">
                            Φόρτωση...
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
                    <!-- 🔔 Notifications Dropdown -->
                    <li class="nav-item dropdown position-relative">
                        <a class="nav-link" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- <i class="bi bi-bell-fill fs-4 text-secondary"></i> -->
                            <span id="notificationCount"
                                  class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm"
                                  style="font-size: 0.75rem; min-width: 22px; height: 22px; line-height: 14px; display:none;">
                                0
                            </span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3"
                            aria-labelledby="notificationDropdown"
                            style="width: 340px; max-height: 420px; overflow-y: auto;">
                            <li>
                                <h6 class="dropdown-header fw-semibold text-center bg-light sticky-top border-bottom">
                                    🔔 Ειδοποιήσεις
                                </h6>
                            </li>
                            <div id="notificationList" class="p-3 small text-muted text-center">
                                Φόρτωση...
                            </div>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- 🔹 Περιεχόμενο Σελίδας -->
    <main class="container">
        {{ $slot }}
    </main>

    <!-- ⚙️ Footer -->
    <footer class="bg-white text-center text-gray-600 py-4 mt-5 border-top">
        <div class="container">
            <p class="mb-1">© {{ date('Y') }} <strong>Eshop Analytics</strong>. Όλα τα δικαιώματα διατηρούνται.</p>
            <p class="small">
                <a href="{{ url('/privacy') }}" class="text-decoration-none text-muted">Πολιτική Απορρήτου</a> ·
                <a href="{{ url('/terms') }}" class="text-decoration-none text-muted">Όροι Χρήσης</a>
            </p>
        </div>
    </footer>

    <!-- 🧠 Notifications Script -->
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const notificationList = document.getElementById('notificationList');
            const notificationCount = document.getElementById('notificationCount');

            async function fetchNotifications() {
                try {
                    const res = await fetch("{{ route('notifications.fetch') }}");
                    const data = await res.json();

                    notificationList.innerHTML = '';
                    let unread = 0;

                    if (data.length === 0) {
                        notificationList.innerHTML = '<p class="text-muted small">Δεν υπάρχουν ειδοποιήσεις.</p>';
                    } else {
                        data.forEach(n => {
                            const item = document.createElement('div');
                            item.classList.add('p-3', 'border-bottom', 'text-start', 'notification-item');
                            item.style.cursor = 'pointer';
                            item.dataset.id = n.id;

                            item.innerHTML = `
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${n.title}</strong><br>
                                        <small class="text-muted">${n.message}</small>
                                    </div>
                                    ${!n.is_read ? '<span class="badge bg-primary ms-2">Νέο</span>' : ''}
                                </div>
                            `;
                            if (!n.is_read) unread++;
                            notificationList.appendChild(item);
                        });
                    }

                    notificationCount.textContent = unread;
                    notificationCount.style.display = unread > 0 ? 'inline-block' : 'none';
                } catch (error) {
                    console.error('Fetch notifications error:', error);
                }
            }

            // Άμεση φόρτωση και κάθε 10"
            fetchNotifications();
            setInterval(fetchNotifications, 10000);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
