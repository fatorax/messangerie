<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RGPDController;

// Authentification
Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
Route::post('/inscription', [AuthController::class, 'register'])->name('register');

Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [AuthController::class, 'login'])->name('login');

Route::get('/email/verify/{email}/{hash}', [AuthController::class, 'verifyEmail'])->name('verify-email');

Route::get('/mot-de-passe-oublie', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/mot-de-passe-oublie', [AuthController::class, 'forgotPassword'])->name('forgot-password');
Route::get('/reinitialiser-mot-de-passe/{email}/{token}', [AuthController::class, 'showResetPassword'])->name('reset-password');
Route::post('/reinitialiser-mot-de-passe/{email}/{token}', [AuthController::class, 'resetPassword'])->name('reset-password');

Route::get('/deconnexion', [AuthController::class, 'logout'])->name('logout');

Route::get('/cgu', [RGPDController::class, 'cgu'])->name('cgu');
Route::get('/mentions-legales', [RGPDController::class, 'mentionsLegales'])->name('mentions-legales');
Route::get('/404', [RGPDController::class, 'page404'])->name('404');

// Protégé par auth
Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/', [MessageController::class, 'dashboard'])->name('dashboard');
    Route::get('/message-sent', [MessageController::class, 'messageSent'])->name('message-sent');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/profil', [MessageController::class, 'profil'])->name('profil');
    Route::get('/parametres', [AuthController::class, 'settings'])->name('settings');
});

// Protégé par admin
Route::group(['middleware' => ['auth', 'verified', 'admin']], function () {
    Route::get('/admin', [AdminController::class, 'admin'])->name('admin');
});