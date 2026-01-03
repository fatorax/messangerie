<?php

use Illuminate\Support\Facades\Route;

// Auth Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Chat Controllers
use App\Http\Controllers\Chat\ConversationController;
use App\Http\Controllers\Chat\MessageController;
use App\Http\Controllers\Chat\FriendRequestController;

// User Controllers
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\User\PasswordController;

// Pages Controllers
use App\Http\Controllers\Pages\HomeController;
use App\Http\Controllers\Pages\LegalController;

// Demo Controllers
use App\Http\Controllers\Demo\DemoAccountController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminController;

// ===========================
// Pages publiques
// ===========================
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/cgu', [LegalController::class, 'terms'])->name('cgu');
Route::get('/mentions-legales', [LegalController::class, 'legalNotice'])->name('mentions-legales');
Route::get('/404', [LegalController::class, 'notFound'])->name('404');

// ===========================
// Authentification
// ===========================
Route::get('/inscription', [RegisterController::class, 'create'])->name('register');
Route::post('/inscription', [RegisterController::class, 'store'])->name('register.submit');

Route::get('/connexion', [LoginController::class, 'create'])->name('login');
Route::post('/connexion', [LoginController::class, 'store'])->name('login.submit');

Route::get('/email/verify/{email}/{hash}', VerifyEmailController::class)->name('verify-email');

Route::get('/mot-de-passe-oublie', [ForgotPasswordController::class, 'create'])->name('forgot-password');
Route::post('/mot-de-passe-oublie', [ForgotPasswordController::class, 'store'])->name('forgot-password.submit');

Route::get('/reinitialiser-mot-de-passe/{email}/{token}', [ResetPasswordController::class, 'edit'])->name('reset-password');
Route::post('/reinitialiser-mot-de-passe/{email}/{token}', [ResetPasswordController::class, 'update'])->name('reset-password.submit');

// ===========================
// Comptes de démonstration
// ===========================
Route::get('/demo', [DemoAccountController::class, 'create'])->name('demo');
Route::post('/demo/send', [DemoAccountController::class, 'store'])->name('demo.store');

// ===========================
// Routes authentifiées
// ===========================
Route::group(['middleware' => ['auth', 'verified']], function () {
    // Déconnexion
    Route::post('/deconnexion', [LoginController::class, 'destroy'])->name('logout');

    // Conversations
    Route::get('/channels', [ConversationController::class, 'index'])->name('dashboard');
    Route::get('/channels/{id}', [ConversationController::class, 'show'])->name('channel.view');
    Route::post('/channels/add', [ConversationController::class, 'store'])->name('channels.store');
    Route::post('/channels/edit', [ConversationController::class, 'update'])->name('channels.update');
    Route::post('/channels/delete', [ConversationController::class, 'destroy'])->name('channels.destroy');
    Route::post('/users/search', [ConversationController::class, 'searchUser'])->name('users.search');

    // Messages
    Route::post('/message-sent', [MessageController::class, 'store'])->name('message.store');
    Route::post('/messages/delete', [MessageController::class, 'destroy'])->name('message.destroy');

    // Demandes d'ami
    Route::post('/friend-request/send', [FriendRequestController::class, 'store'])->name('friend-request.store');
    Route::post('/friend-request/accept', [FriendRequestController::class, 'accept'])->name('friend-request.accept');
    Route::post('/friend-request/reject', [FriendRequestController::class, 'reject'])->name('friend-request.reject');
    Route::post('/friend-request/cancel', [FriendRequestController::class, 'cancel'])->name('friend-request.cancel');
    Route::post('/friend-request/pending', [FriendRequestController::class, 'index'])->name('friend-request.index');

    // Paramètres utilisateur
    Route::get('/parametres', [SettingsController::class, 'index'])->name('settings');
    Route::post('/parametres', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/parametres/delete', [SettingsController::class, 'destroy'])->name('settings.destroy');

    // Mot de passe
    Route::get('/parametres/mot-de-passe', [PasswordController::class, 'edit'])->name('password.edit');
    Route::post('/parametres/mot-de-passe', [PasswordController::class, 'update'])->name('password.update');
});

// ===========================
// Routes administrateur
// ===========================
Route::group(['middleware' => ['auth', 'verified', 'admin']], function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
});