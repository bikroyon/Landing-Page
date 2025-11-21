<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomizationController;
use App\Http\Controllers\DeliveryZoneController;
use App\Http\Controllers\FraudCheckerController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OthersPageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleAccessController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StoreSettingController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

// Guest landing page
Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::post('/order', [LandingPageController::class, 'store'])->name('order.store');
Route::get('/thank-you/{order_number}', [LandingPageController::class, 'thankYou'])->name('order.thankyou');
// Dynamic Frontend Page
Route::get('/pages/{slug}', [OthersPageController::class, 'showPublicPage'])
    ->name('page.view');


// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);

    // Admin Orders Management
    Route::put('/orders/{order_number}/status', [OrderController::class, 'updateStatus'])
        ->name('orders.updateStatus');
    Route::resource('orders', OrderController::class)->except(['store', 'create']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');


    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my');
    // Toggle customer ban status
    Route::resource('customers', CustomerController::class)
        ->only(['index', 'destroy']);
    Route::post('/customers/{customer}/toggle-ban', [CustomerController::class, 'toggleBan'])
        ->name('customers.toggleBan');

    // Admin
    Route::get('/reviews', [ReviewController::class, 'index'])->name('admin.reviews.index');
    Route::post('/reviews/{review}/toggle-publish', [ReviewController::class, 'togglePublish'])->name('admin.reviews.toggle-publish');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');

    // User
    Route::post('/reviews/{product}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::get('/my-reviews', [ReviewController::class, 'myReviews'])->name('reviews.my');


    // Customer review routes
    Route::post('/reviews/{product}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/update', [ReviewController::class, 'update'])->name('reviews.update');

    // Delivery Zone Management
    Route::resource('delivery-zones', DeliveryZoneController::class);
    Route::post('delivery-zones/{deliveryZone}/toggle-status', [DeliveryZoneController::class, 'toggleStatus'])
        ->name('delivery-zones.toggleStatus');

    // Store Settings
    Route::get('/store-settings', [StoreSettingController::class, 'index'])->name('settings.index');
    Route::get('/store-settings/edit', [StoreSettingController::class, 'edit'])->name('settings.edit');
    Route::post('/store-settings/update', [StoreSettingController::class, 'update'])->name('settings.update');

    // CUSTOMER support tickets
    Route::get('/tickets', [SupportController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [SupportController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [SupportController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [SupportController::class, 'show'])->name('tickets.show');

    //for admin support tickets
    Route::get('/supports', [SupportController::class, 'adminIndex'])->name('admin.tickets.index');
    Route::put('/supports/{ticket}/status', [SupportController::class, 'updateStatus'])->name('admin.tickets.status');
    Route::get('/supports/{ticket}', [SupportController::class, 'adminShow'])
        ->name('admin.tickets.show');
    Route::put('/supports/{ticket}/reply', [SupportController::class, 'adminReply'])->name('admin.tickets.reply');

    // Others Pages Management
    Route::get('/pages', [OthersPageController::class, 'index'])->name('admin.pages.index');
    Route::get('/pages/create', [OthersPageController::class, 'create'])->name('admin.pages.create');
    Route::post('/pages', [OthersPageController::class, 'store'])->name('admin.pages.store');
    Route::get('/pages/{page}/edit', [OthersPageController::class, 'edit'])->name('admin.pages.edit');
    Route::put('/pages/{page}', [OthersPageController::class, 'update'])->name('admin.pages.update');

    //Roles
    Route::resource('roles', RoleController::class);
    Route::get('/roles/{role}/permissions', [RoleAccessController::class, 'edit'])->name('roles.access.edit');
    Route::post('/roles/{role}/permissions', [RoleAccessController::class, 'update'])->name('roles.access.update');

    // Fraud Checker 
    Route::get('/fraud-checker', [FraudCheckerController::class, 'index'])->name('fraud.index');
    Route::post('/fraud-checker/search', [FraudCheckerController::class, 'search'])->name('fraud.search');
});
