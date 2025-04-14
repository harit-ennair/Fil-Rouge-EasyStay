<?php

use App\Http\Controllers\AppartementsController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'AuthLogin']);

Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/register', [UserController::class, 'store']);

Route::get('/logout', [UserController::class, 'logout'])->name('logout');


Route::get('/appartements', [AppartementsController::class, 'index'])->name('appartements_index');
Route::get('/appartements/create', [AppartementsController::class, 'create'])->name('appartements_create');
Route::post('/appartements/store', [AppartementsController::class, 'store'])->name('appartements_store');
Route::get('/appartements/{id}/edit', [AppartementsController::class, 'edit'])->name('appartements_edit');
Route::post('/appartements/{id}/update', [AppartementsController::class, 'update'])->name('appartements_update');
Route::get('/appartements/{id}/delete', [AppartementsController::class, 'delete'])->name('appartements_delete');
