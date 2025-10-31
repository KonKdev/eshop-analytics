<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyReportMail;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\AiForecastController;
use App\Http\Controllers\InsightsController;
use App\Http\Controllers\MarketingPreviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\ProductInsightsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Services\WooCommerceService;
use App\Models\Store;
use App\Http\Controllers\WooController;

Route::get('/', function () {
    return view('landing');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports/export', [DashboardController::class, 'exportCsv'])->name('reports.export.csv');
    Route::post('/reports/email', [DashboardController::class, 'sendReportEmail'])->name('reports.email');

    Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');
    Route::get('/stores/create', [StoreController::class, 'create'])->name('stores.create');
    Route::post('/stores', [StoreController::class, 'store'])->name('stores.store');
    Route::get('/stores/{store}/orders', [StoreController::class, 'orders'])->name('stores.orders');
    Route::delete('/stores/{store}', [StoreController::class, 'destroy'])->name('stores.destroy');

    Route::post('/sync/products', [SyncController::class, 'syncProducts'])->name('sync.products');
    Route::post('/sync/orders', [SyncController::class, 'syncOrders'])->name('sync.orders');

    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/sync', [InventoryController::class, 'sync'])->name('inventory.sync');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/insights', [ProductInsightsController::class, 'index'])->name('insights.index');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/fetch', [NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

Route::get('/test-woocommerce/{store}', function (Store $store) {
    $service = new WooCommerceService($store);
    $orders = $service->getOrders();
    dd($orders);
});

Route::get('/test-email', function () {
    Mail::to('test@example.com')->send(new DailyReportMail(7, 199.99));
    return "✅ Email στάλθηκε (δες το Mailtrap inbox σου)";
});

Route::middleware(['auth'])->group(function () {
    Route::get('/orders/{store}', [StoreController::class, 'orders'])->name('orders.index');
});

Route::get('/ai-forecast', [AiForecastController::class, 'index'])->name('ai.forecast');

Route::prefix('marketing')->group(function () {
    Route::get('/preview/low-sales/{store}', [MarketingController::class, 'previewLowSales'])->name('marketing.preview.lowSales');
    Route::post('/campaign/low-sales/{store}', [MarketingController::class, 'campaignLowSales'])->name('marketing.campaign.lowSales');
    Route::post('/discount/best-sellers/{store}', [MarketingController::class, 'discountBestSellers'])->name('marketing.discount.bestSellers');
    Route::post('/email/winback/{store}', [MarketingController::class, 'emailWinback'])->name('marketing.email.winback');
    Route::post('/review/images/{store}', [MarketingController::class, 'reviewImages'])->name('marketing.review.images');
});

Route::get('/marketing/{store}/preview/low-sales', [MarketingController::class, 'previewLowSales'])->name('marketing.preview.lowSalesAlt');
Route::get('/marketing/{store}/preview/best-sellers', [MarketingController::class, 'previewBestSellers'])->name('marketing.preview.bestSellersAlt');
Route::get('/marketing/{store}/preview/winback', [MarketingController::class, 'previewWinback'])->name('marketing.preview.winbackAlt');
Route::get('/marketing/{store}/preview/review-images', [MarketingController::class, 'previewReviewImages'])->name('marketing.preview.reviewImagesAlt');

Route::prefix('marketing/{store}')->group(function () {
    Route::get('/preview/low-sales', [MarketingPreviewController::class, 'previewLowSales'])->name('marketing.preview.lowSalesPreview');
    Route::get('/preview/best-sellers', [MarketingPreviewController::class, 'previewBestSellers'])->name('marketing.preview.bestSellersPreview');
    Route::get('/preview/winback', [MarketingPreviewController::class, 'previewWinback'])->name('marketing.preview.winbackPreview');
    Route::get('/preview/review-images', [MarketingPreviewController::class, 'previewReviewImages'])->name('marketing.preview.reviewImagesPreview');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/store/{store}/orders', [WooController::class, 'fetchOrders'])->name('store.orders');
});

Route::post('/sync/products/{store}', [SyncController::class, 'syncProducts'])->name('sync.products.store');
Route::post('/sync/orders/{store}', [SyncController::class, 'syncOrders'])->name('sync.orders.store');

require __DIR__ . '/auth.php';
