<?php

use App\Http\Controllers\Api\FirstAccessController;
use App\Http\Controllers\Api\HostController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Models\User;
use Laravel\Pail\ValueObjects\Origin\Console;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/login', function () {
    return view('index');
})->name('login');

Route::get('/signUp', function () {
    return view('signUp');
})->name('signUp');

Route::get('/home', function () {
    return view('homepage');
})->name('homepage');

Route::post('/login', [UserController::class, 'getLoginUser'])->name('login.post');
Route::post('/create-user', [UserController::class,'store'])->name('users.store');

Route::middleware('auth')->group(function () {
    Route::get('/authenticated-user', [UserController::class, 'getAuthenticatedUser'])->name('user.authenticated');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout-user', [UserController::class, 'logoutUser'])->name('user.logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/firstAccess', [FirstAccessController::class, 'showFirstAccessForm'])->name('firstAccess.show');
});

Route::middleware('auth')->group(function () {
    Route::post('/create-firstAccess', [FirstAccessController::class, 'createFirstAccessForm'])->name('firstAccess.create');
});

Route::middleware('auth')->group(function () {
    Route::get('/invitation/{token}', [HostController::class, 'storeGuestToken'])->name('host.showInvitation');
    Route::post('/invitation/{token}', [HostController::class, 'storeGuestToken'])->name('host.storeToken');
});

Route::middleware('auth')->group(function () {
    Route::get('/invitation', [HostController::class, 'showChooseHostData'])->name('host.choose');
});

Route::middleware('auth')->group(function () {
    Route::get('/host', [HostController::class, 'showHostData'])->name('host.view');
});

Route::middleware('auth')->group(function () {
    Route::post('/create-product', [ProductController::class, 'store'])->name('product.store');
});

Route::middleware('auth')->group(function () {
    Route::delete('/products/{productId}', [ProductController::class, 'removeProduct'])->name('product.remove');
});

Route::middleware('auth')->group(function () {
    Route::post('/choose-product/{productId}', [ProductController::class, 'storeProductId'])->name('product.store');
});



