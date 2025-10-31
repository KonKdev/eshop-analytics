<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ShopMetrics - Smart Analytics for WooCommerce</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <meta name="description" content="ShopMetrics helps WooCommerce store owners track sales, monitor inventory, and boost profits with AI-powered analytics.">
    <meta property="og:image" content="{{ asset('images/preview.jpg') }}">
    <meta property="og:title" content="ShopMetrics – Smart Analytics for WooCommerce">
    <meta property="og:description" content="AI-powered analytics for your online store.">

</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
          <div class="mb-6">
            <img src="{{ asset('images/logo.svg') }}" alt="ShopMetrics" class="h-24 mx-auto">
        </div>
        </div>


        <nav>
            <a href="{{ route('login') }}" class="btn-nav">Login</a>
            <a href="{{ route('register') }}" class="btn-nav btn-nav-primary">Register</a>
        </nav>
    </header>

    <!-- Hero -->
    <main class="hero">
        <h1>Smart analytics for your WooCommerce store</h1>
        <p>Track sales, monitor inventory, and receive daily insights to grow your business.</p>
        <div class="cta-buttons">
            <a href="{{ route('register') }}" class="btn btn-primary">🚀 Get Started</a>
            <a href="{{ route('login') }}" class="btn btn-secondary">Already a member?</a>
        </div>
    </main>

    <!-- Features -->
    <section class="features">
        <div class="feature">
            <h3>📈 Sales Reports</h3>
            <p>Daily, weekly and monthly reports on your store’s performance.</p>
        </div>
        <div class="feature">
            <h3>📦 Inventory</h3>
            <p>Monitor stock levels and get alerts for low inventory products.</p>
        </div>
        <div class="feature">
            <h3>📧 Email Alerts</h3>
            <p>Get notified with daily summaries of orders and sales.</p>
        </div>
    </section>

<section class="how-it-works">
  <h2>How it works</h2>
  <div class="steps">
    <div><span>1️⃣</span> Connect your WooCommerce store</div>
    <div><span>2️⃣</span> Get instant sales insights</div>
    <div><span>3️⃣</span> Receive AI-driven recommendations</div>
  </div>
</section>

<section class="pricing">
  <h2 class="pricing-title">Simple Pricing</h2>
  <p class="pricing-subtitle">Choose the plan that works for you</p>

  <div class="pricing-cards">
    <div class="card">
      <h3 class="plan">Free</h3>
      <p class="price">€0 / month</p>
      <p class="desc">Basic analytics and daily reports.</p>
      <a href="{{ route('register') }}" class="btn-pricing">Get Started</a>
    </div>

    <div class="card">
      <h3 class="plan">Pro</h3>
      <p class="price">€19 / month</p>
      <p class="desc">Full access to reports and inventory alerts.</p>
      <a href="{{ route('register') }}" class="btn-pricing">Start Free Trial</a>
    </div>
  </div>
</section>





<section class="trial-section">
    <div class="trial-wrapper">
        <h2 class="trial-title">Try ShopMetrics for Free</h2>
        <p class="trial-subtitle">Enjoy 14 days of full access — no credit card required.</p>
        <a href="{{ route('register') }}" class="trial-button">Start Free Trial</a>
    </div>
</section>






    <!-- Footer -->
  
<footer>
  <p>&copy; {{ date('Y') }} ShopMetrics. All rights reserved.</p>
  <p><a href="{{ route('register') }}" class="footer-cta">🚀 Try it Free</a></p>
</footer>




</body>
</html>
