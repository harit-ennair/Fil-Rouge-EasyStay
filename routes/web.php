<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppartementsController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OwnerPropertiesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CategoriesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'AuthLogin']);

Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/register', [UserController::class, 'store']);

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/', [AppartementsController::class, 'index'])->name('appartements_index');
Route::get('/appartements', [AppartementsController::class, 'index'])->name('appartements_index');




// Admin routes
Route::middleware(['is.admin'])->group(function () {
    
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin_dashboard');
    
    // user routes
    Route::get('/allOwner', [OwnerController::class, 'index'])->name('allOwner');
    Route::get('/allClients', [ClientController::class, 'index'])->name('allClients');
    Route::get('/users/{id}/delete', [AdminController::class, 'deleteUser'])->name('user_delete');
    
    //apprtements routes
    Route::get('/admin/all-properties', [AppartementsController::class, 'allProperties'])->name('admin.all-properties');
    Route::get('/all-properties', [AppartementsController::class, 'allProperties'])->name('all_properties');
    
    
    // Categories routes
    Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoriesController::class, 'destroy'])->name('categories.destroy');
});



// Owner routes
Route::middleware(['is.owner'])->group(function () {
    
    // owner routes
    Route::get('/ownerDashboard', [OwnerController::class, 'dashboard'])->name('owner_dashboard');
    
    //appartements routes
    
    Route::get('/appartements/create', [AppartementsController::class, 'create'])->name('appartements_create');
    Route::post('/appartements/store', [AppartementsController::class, 'store'])->name('appartements_store');
    Route::get('/appartements/{id}/edit', [AppartementsController::class, 'edit'])->name('appartements_edit');
    Route::post('/appartements/{id}/update', [AppartementsController::class, 'update'])->name('appartements_update');
    Route::get('/appartements/{id}/delete', [AppartementsController::class, 'delete'])->name('appartements_delete');
    Route::post('/reservations/{id}/confirm', [ReservationController::class, 'confirm'])->name('reservation.confirm');
    Route::post('/reservations/{id}/decline', [ReservationController::class, 'decline'])->name('reservation.decline');
    Route::get('/owner/properties', [OwnerPropertiesController::class, 'index'])->name('owner.properties');
    Route::get('/owner/properties/{id}/delete', [OwnerPropertiesController::class, 'delete'])->name('owner.properties.delete');

    
    
    // user routes
    Route::get('/owners/{id}/profile', [OwnerController::class, 'show'])->name('owner_profile');
    
    // reservation routes

});


     Route::get('/appartements/{id}', [AppartementsController::class, 'show'])->name('appartements_show');






Route::middleware(['is.client'])->group(function () {
    // user routes
    Route::get('/clients/{id}/profile', [ClientController::class, 'show'])->name('client_profile');

});


// auth routes
Route::middleware(['auth'])->group(function () {


            Route::post('/appartements/{id}/reserve', [ReservationController::class, 'store'])->name('appartements_reserve');
            Route::post('/appartements/{id}', [ReservationController::class, 'store'])->name('appartements_reservations');
            Route::post('/payments/{reservationId}/capture', [PaymentController::class, 'capturePayment'])->name('payments.capture');
            Route::post('/payments/{reservationId}/cancel', [PaymentController::class, 'cancelPayment'])->name('payments.cancel');
            Route::get('/reservations', [ReservationController::class, 'store'])->name('reservations');
            Route::get('/users', [UserController::class, 'users'])->name('users');
            Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('user_edit');
            Route::post('/users/{id}/update', [UserController::class, 'update'])->name('user_update');
            Route::get('/payments/{reservationId}', [PaymentController::class, 'showPaymentForm'])->name('payments.form');
            Route::post('/payments/{reservationId}/process', [PaymentController::class, 'processPayment'])->name('payments.process');
            Route::get('/payments/{reservationId}/confirm', [PaymentController::class, 'confirmPayment'])->name('payments.confirm');
    });











