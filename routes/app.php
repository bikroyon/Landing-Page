<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryZoneController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StoreSettingController;
use Illuminate\Support\Facades\Route;

// Guest landing page
Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::post('/order', [LandingPageController::class, 'store'])->name('order.store');
Route::get('/thank-you/{order}', [LandingPageController::class, 'thankYou'])->name('order.thankyou');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);

    // Admin Orders Management
    Route::resource('orders', OrderController::class)->except(['store', 'create']);
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my');
    Route::resource('customers', CustomerController::class)
        ->only(['index', 'destroy']); // Index and Delete

    Route::post('/customers/{customer}/toggle-ban', [CustomerController::class, 'toggleBan'])
        ->name('customers.toggleBan');
    Route::get('/reviews', [ReviewController::class, 'index'])->name('admin.reviews.index');
    Route::post('/reviews/{review}/toggle-publish', [ReviewController::class, 'togglePublish'])->name('admin.reviews.toggle-publish');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');
    Route::get('/my-reviews', [ReviewController::class, 'myReviews'])->name('reviews.my');
    
    // Customer review routes
    Route::post('/reviews/{product}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/update', [ReviewController::class, 'update'])->name('reviews.update');

    // Delivery Zone Management
    Route::resource('delivery-zones', DeliveryZoneController::class);
    Route::post('delivery-zones/{deliveryZone}/toggle-status', [DeliveryZoneController::class, 'toggleStatus'])
        ->name('delivery-zones.toggleStatus');

    Route::get('/store-settings', [StoreSettingController::class, 'index'])->name('settings.index');
    Route::get('/store-settings/edit', [StoreSettingController::class, 'edit'])->name('settings.edit');
    Route::post('/store-settings/update', [StoreSettingController::class, 'update'])->name('settings.update');
});
