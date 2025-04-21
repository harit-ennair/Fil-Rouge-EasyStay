<?php

use App\Http\Controllers\AppartementsController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OwnerPropertiesController;
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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'AuthLogin']);

Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/register', [UserController::class, 'store']);

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Public routes - accessible to all
Route::get('/', [AppartementsController::class, 'index'])->name('appartements_index');
Route::get('/appartements', [AppartementsController::class, 'index'])->name('appartements_index');

// Owner routes - for property owners
// Route::middleware(['auth', 'is.owner'])->group(function () {
    Route::get('/appartements/create', [AppartementsController::class, 'create'])->name('appartements_create');
    Route::post('/appartements/store', [AppartementsController::class, 'store'])->name('appartements_store');
    Route::get('/appartements/{id}/edit', [AppartementsController::class, 'edit'])->name('appartements_edit');
    Route::post('/appartements/{id}/update', [AppartementsController::class, 'update'])->name('appartements_update');
    Route::get('/appartements/{id}/delete', [AppartementsController::class, 'delete'])->name('appartements_delete');
    Route::get('/ownerDashboard', [UserController::class, 'ownerDashboard'])->name('owner_dashboard');
    Route::post('/reservations/{id}/confirm', [ReservationController::class, 'confirm'])->name('reservation.confirm');
    Route::post('/reservations/{id}/decline', [ReservationController::class, 'decline'])->name('reservation.decline');
    // Owner Properties Routes
    Route::get('/owner/properties', [OwnerPropertiesController::class, 'index'])->name('owner.properties');
    Route::get('/owner/properties/{id}/delete', [OwnerPropertiesController::class, 'delete'])->name('owner.properties.delete');
// });

Route::get('/appartements/{id}', [AppartementsController::class, 'show'])->name('appartements_show');
// User routes - for standard users
// Route::middleware(['auth', 'is.client'])->group(function () {
    Route::post('/appartements/{id}/reserve', [ReservationController::class, 'store'])->name('appartements_reserve');
    Route::post('/appartements/{id}', [ReservationController::class, 'store'])->name('appartements_reservations');
// });

// Admin routes
// Route::middleware(['auth', 'is.admin'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('admin_dashboard');
    Route::get('/users', [UserController::class, 'users'])->name('users');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('user_edit');
    Route::post('/users/{id}/update', [UserController::class, 'update'])->name('user_update');
    Route::get('/users/{id}/delete', [UserController::class, 'delete'])->name('user_delete');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations');
    Route::get('/reservations/{id}/edit', [ReservationController::class, 'edit'])->name('reservation_edit');
    Route::post('/reservations/{id}/update', [ReservationController::class, 'update'])->name('reservation_update');
    Route::get('/reservations/{id}/delete', [ReservationController::class, 'delete'])->name('reservation_delete');
    Route::get('/appartements/{id}/approve', [AppartementsController::class, 'approve'])->name('appartements_approve');
    Route::get('/appartements/{id}/reject', [AppartementsController::class, 'reject'])->name('appartements_reject');
    Route::get('/owners/{id}/profile', [UserController::class, 'showProfile'])->name('owner_profile');
    Route::get('/clients/{id}/profile', [UserController::class, 'ClientProfile'])->name('client_profile');
    Route::get('/admin/all-properties', [AppartementsController::class, 'allProperties'])->name('admin.all-properties');
    Route::get('/all-properties', [AppartementsController::class, 'allProperties'])->name('all_properties');
// });