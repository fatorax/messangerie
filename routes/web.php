<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RGPDController;

// Authentification
Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
Route::post('/inscription', [AuthController::class, 'register'])->name('register');

Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [AuthController::class, 'login'])->name('login');

Route::get('/mot-de-passe-oublie', [AuthController::class, 'forgotPassword'])->name('forgot-password');
Route::get('/reinitialiser-mot-de-passe', [AuthController::class, 'resetPassword'])->name('reset-password');

Route::get('/deconnexion', [AuthController::class, 'logout'])->name('logout');

// Route::get('/cgu', [RGPDController::class, 'cgu'])->name('cgu');
// Route::get('/mentions-legales', [RGPDController::class, 'mentionsLegales'])->name('mentions-legales');
// Route::get('/404', [RGPDController::class, 'page404'])->name('404');

// Protégé par auth
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [ChatController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', [ChatController::class, 'profil'])->name('profil');
    Route::get('/parametres', [AuthController::class, 'parametres'])->name('parametres');
});

// Protégé par admin
Route::group(['middleware' => 'admin'], function () {
    Route::get('/admin', [AdminController::class, 'admin'])->name('admin');
});