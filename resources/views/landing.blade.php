<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ShopMetrics - Smart Analytics for WooCommerce</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
  
</head>

<body>

<header class="navbar p-3 px-4 shadow-sm bg-white sticky-top navbar-light">
    <div class="container-fluid">
        
        <a class="navbar-brand" href="#">
            <div class="logo d-flex align-items-center">
                <img src="{{ asset('images/logo.svg') }}" alt="ShopMetrics">
            </div>
        </a>

        <nav class="d-none d-sm-block">
            <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
        </nav>

        <button class="navbar-toggler d-block d-sm-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title fw-bold" id="offcanvasNavbarLabel">ShopMetrics Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column gap-3">
                <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary w-100">Register</a>
            </div>
        </div>
    </div>
</header>

<main class="hero text-center py-5" data-aos="fade-up">
    <div class="container">
        <h1 class="display-5 fw-bold text-dark mb-3">
            Boost your eCommerce sales with <span class="text-primary">AI-powered analytics</span>
        </h1>
        <p class="lead text-muted mb-4">
          Connect your WooCommerce, Shopify or Magento store and unlock data-driven growth.    <!-- Track sales, monitor stock, and receive daily insights to grow your business. -->
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">🚀 Start Free Analytics</a>
            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">Login to Dashboard</a>
        </div>
    </div>
</main>

<section class="container text-center py-5" data-aos="fade-up">
    <h2 class="fw-bold mb-5">Key Features</h2>
    <div class="row g-4">
        <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
            <div class="p-4 border rounded-4 feature-card h-100">
                <h3>📈 Sales Reports</h3>
                <p class="text-muted">Daily, weekly and monthly performance reports.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
            <div class="p-4 border rounded-4 feature-card h-100">
                <h3>📦 Inventory</h3>
                <p class="text-muted">Monitor stock levels and get low-stock alerts instantly.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="zoom-in" data-aos-delay="300">
            <div class="p-4 border rounded-4 feature-card h-100">
                <h3>🤖 AI Insights</h3>
                <p class="text-muted">Get personalized suggestions to grow your store revenue.</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-primary text-white py-5 text-center" data-aos="fade-up">
    <div class="container">
        <h2 class="fw-bold mb-4">How it Works</h2>
        <div class="row g-4">
            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
                <div class="p-4 bg-white text-dark rounded-4 shadow-sm h-100">
                    <span class="fs-1">1️⃣</span>
                    <h5 class="mt-3">Connect Your Store</h5>
                    <p class="text-muted">Link your WooCommerce shop securely in one click.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
                <div class="p-4 bg-white text-dark rounded-4 shadow-sm h-100">
                    <span class="fs-1">2️⃣</span>
                    <h5 class="mt-3">Analyze Data</h5>
                    <p class="text-muted">Visualize sales and discover hidden opportunities.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="300">
                <div class="p-4 bg-white text-dark rounded-4 shadow-sm h-100">
                    <span class="fs-1">3️⃣</span>
                    <h5 class="mt-3">Boost Sales</h5>
                    <p class="text-muted">Act on AI recommendations to improve conversions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="text-center py-5 bg-white" data-aos="fade-up">
    <div class="container">
        <h2 class="fw-bold mb-3">Simple Pricing</h2>
        <p class="text-muted mb-5">Choose the plan that fits your business</p>

        <div class="row justify-content-center g-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <h3 class="card-title">Free</h3>
                        <p class="display-6 fw-bold">€0 <small class="fs-6 text-muted">/ month</small></p>
                        <p>Basic analytics and daily reports.</p>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">Get Started</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <h3 class="card-title">Pro</h3>
                        <p class="display-6 fw-bold">€19 <small class="fs-6 text-muted">/ month</small></p>
                        <p>Full access to reports, insights, and inventory alerts.</p>
                        <a href="{{ route('register') }}" class="btn btn-primary w-100">Start Free Trial</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="text-center py-5 bg-light" data-aos="zoom-in-up">
    <div class="container">
        <h2 class="fw-bold mb-3 text-primary">Try ShopMetrics for Free</h2>
        <p class="text-muted mb-4">Enjoy 14 days of full access — no credit card required.</p>
        <a href="{{ route('register') }}" class="btn btn-lg btn-primary">Start Free Trial</a>
    </div>
</section>

<footer class="text-center py-4 bg-dark text-white">
    <p class="mb-1">&copy; {{ date('Y') }} ShopMetrics. All rights reserved.</p>
    <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm mt-2">🚀 Try it Free</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });
</script>

</body>
</html>