<?php

use App\Http\Controllers\BookCategoryController;
use App\Http\Controllers\SubscriptionPackageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $subscriptionPackages = App\Models\SubscriptionPackage::all();
    return view('home', compact('subscriptionPackages'));
})->name('home');

Route::middleware(['IsLoggedIn'])->group(function () {
    Route::get('/logout', [
        UserController::class,
        'logout'
    ])->name('logout');

    Route::prefix('admin')->name('admin.')->middleware('IsAdmin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('book-categories', BookCategoryController::class);
        Route::resource('subscription-packages', SubscriptionPackageController::class);
    });
});

Route::middleware(['IsGuest'])->group(function () {

    Route::get('/register', function () {
        return view('register');
    })->name('register');

    Route::post('/register', [
        UserController::class,
        'register'
    ])->name('register.store')->middleware('throttle:5,1');

    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::post('/login', [
        UserController::class,
        'login'
    ])->name('login.store')->middleware('throttle:5,1');
});
