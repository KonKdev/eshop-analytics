<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyReportMail;

use App\Http\Controllers\{
    MarketingController,
    MarketingPreviewController,
    AiForecastController,
    InsightsController,
    DashboardController,
    ProfileController,
    StoreController,
    InventoryController,
    SyncController,
    ProductInsightsController,
    NotificationController,
    ReportController,
    WooController
};

use App\Services\WooCommerceService;
use App\Models\Store;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
});

/*
|--------------------------------------------------------------------------
| 🔐 Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // 👤 Προφίλ Χρήστη
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🏠 Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports/export', [DashboardController::class, 'exportCsv'])->name('reports.export.csv');
    Route::post('/reports/email', [DashboardController::class, 'sendReportEmail'])->name('reports.email');

    // 🏪 Καταστήματα
    Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');
    Route::get('/stores/create', [StoreController::class, 'create'])->name('stores.create');
    Route::post('/stores', [StoreController::class, 'store'])->name('stores.store');
    Route::get('/stores/{store}/orders', [StoreController::class, 'orders'])->name('stores.orders');
    Route::delete('/stores/{store}', [StoreController::class, 'destroy'])->name('stores.destroy');

    // 🔄 Συγχρονισμοί
    Route::post('/sync/products/{store}', [SyncController::class, 'syncProducts'])->name('sync.products');
    Route::post('/sync/orders/{store}', [SyncController::class, 'syncOrders'])->name('sync.orders');

    // 📦 Απόθεμα
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/sync', [InventoryController::class, 'sync'])->name('inventory.sync');

    // 📊 Αναφορές
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // 💡 Insights
    Route::get('/insights', [ProductInsightsController::class, 'index'])->name('insights.index');

    // 🔔 Ειδοποιήσεις
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/fetch', [NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // 🧾 WooCommerce Orders (για προβολή από Store)
    Route::get('/store/{store}/orders', [WooController::class, 'fetchOrders'])->name('store.orders');
});

/*
|--------------------------------------------------------------------------
| 🧪 Test Routes (μόνο για dev)
|--------------------------------------------------------------------------
*/
Route::get('/test-woocommerce/{store}', function (Store $store) {
    $service = new WooCommerceService($store);
    $orders = $service->getOrders();
    dd($orders);
});

Route::get('/test-email', function () {
    Mail::to('test@example.com')->send(new DailyReportMail(7, 199.99));
    return "✅ Email στάλθηκε (δες το Mailtrap inbox σου)";
});

/*
|--------------------------------------------------------------------------
| 🤖 AI Forecast
|--------------------------------------------------------------------------
*/
Route::get('/ai-forecast', [AiForecastController::class, 'index'])->name('ai.forecast');

/*
|--------------------------------------------------------------------------
| 📈 Marketing Routes
|--------------------------------------------------------------------------
*/
Route::prefix('marketing/{store}')->group(function () {
    Route::get('/preview/low-sales', [MarketingPreviewController::class, 'previewLowSales'])->name('marketing.preview.lowSales');
    Route::get('/preview/best-sellers', [MarketingPreviewController::class, 'previewBestSellers'])->name('marketing.preview.bestSellers');
    Route::get('/preview/winback', [MarketingPreviewController::class, 'previewWinback'])->name('marketing.preview.winback');
    Route::get('/preview/review-images', [MarketingPreviewController::class, 'previewReviewImages'])->name('marketing.preview.reviewImages');

    Route::post('/campaign/low-sales', [MarketingController::class, 'campaignLowSales'])->name('marketing.campaign.lowSales');
    Route::post('/discount/best-sellers', [MarketingController::class, 'discountBestSellers'])->name('marketing.discount.bestSellers');
    Route::post('/email/winback', [MarketingController::class, 'emailWinback'])->name('marketing.email.winback');
    Route::post('/review/images', [MarketingController::class, 'reviewImages'])->name('marketing.review.images');
});

/*
|--------------------------------------------------------------------------
| Auth routes (login/register)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
