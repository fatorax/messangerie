<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RGPDController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\TestController;

// Authentification
Route::get('/', [Controller::class, 'index'])->name('index');

Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
Route::post('/inscription', [AuthController::class, 'register'])->name('register.submit');

Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [AuthController::class, 'login'])->name('login.submit');

Route::get('/email/verify/{email}/{hash}', [AuthController::class, 'verifyEmail'])->name('verify-email');

Route::get('/mot-de-passe-oublie', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/mot-de-passe-oublie', [AuthController::class, 'forgotPassword'])->name('forgot-password.submit');
Route::get('/reinitialiser-mot-de-passe/{email}/{token}', [AuthController::class, 'showResetPassword'])->name('reset-password');
Route::post('/reinitialiser-mot-de-passe/{email}/{token}', [AuthController::class, 'resetPassword'])->name('reset-password.submit');

Route::get('/demo', [TestController::class, 'store'])->name('test');
Route::post('/demo/send', [TestController::class, 'send'])->name('test-send');

Route::get('/cgu', [RGPDController::class, 'cgu'])->name('cgu');
Route::get('/mentions-legales', [RGPDController::class, 'mentionsLegales'])->name('mentions-legales');
Route::get('/404', [RGPDController::class, 'page404'])->name('404');

// Protégé par auth
Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/channels', [ChannelController::class, 'dashboard'])->name('dashboard');
    Route::get('/channels/{id}', [ChannelController::class, 'viewChannel'])->name('channel.view');
    Route::post('/channels/add', [ChannelController::class, 'addchannels'])->name('channels.add');
    Route::post('/channels/edit', [ChannelController::class, 'editchannels'])->name('channels.edit');
    Route::post('/channels/delete', [ChannelController::class, 'deletechannels'])->name('channels.delete');
    Route::post('/channels/list', [ChannelController::class, 'listchannels'])->name('channels.list');
    Route::post('/users/search', [ChannelController::class, 'searchUserAdd'])->name('users.search');

    // Routes pour les demandes d'ami
    Route::post('/friend-request/send', [FriendRequestController::class, 'send'])->name('friend-request.send');
    Route::post('/friend-request/accept', [FriendRequestController::class, 'accept'])->name('friend-request.accept');
    Route::post('/friend-request/reject', [FriendRequestController::class, 'reject'])->name('friend-request.reject');
    Route::post('/friend-request/cancel', [FriendRequestController::class, 'cancel'])->name('friend-request.cancel');
    Route::post('/friend-request/pending', [FriendRequestController::class, 'pendingRequests'])->name('friend-request.pending');

    Route::post('/message-sent', [MessageController::class, 'messageSent'])->name('message-sent');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/delete', [MessageController::class, 'delete'])->name('messages.delete');
    Route::get('/profil', [MessageController::class, 'profil'])->name('profil');
    Route::get('/parametres', [UserController::class, 'settings'])->name('settings');
    Route::post('/parametres', [UserController::class, 'updateSettings'])->name('user.settings.update');
    Route::get('/parametres/mot-de-passe', [UserController::class, 'changePassword'])->name('user.password.change');
    Route::post('/parametres/mot-de-passe', [UserController::class, 'updatePassword'])->name('user.password.update');
    Route::get('/parametres/delete', [UserController::class, 'deleteAccount'])->name('user.delete');

    Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');
});

// Protégé par admin
Route::group(['middleware' => ['auth', 'verified', 'admin']], function () {
    Route::get('/admin', [AdminController::class, 'admin'])->name('admin');
});