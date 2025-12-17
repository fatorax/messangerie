<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RGPDController;
use App\Http\Controllers\ChannelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

Route::get('/', function () {
    return redirect('/channels');
});

// Protégé par auth
Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/channels', [ChannelController::class, 'dashboard'])->name('dashboard');
    Route::get('/channels/{id}', [ChannelController::class, 'viewChannel'])->name('channels.view');
    Route::post('/channels/add', [ChannelController::class, 'addchannels'])->name('channels.add');
    Route::post('/channels/edit', [ChannelController::class, 'addchannels'])->name('channels.add');
    Route::post('/channels/delete', [ChannelController::class, 'deletechannels'])->name('channels.delete');
    Route::post('/channels/list', [ChannelController::class, 'listchannels'])->name('channels.list');
    Route::post('/users/search', [ChannelController::class, 'searchUserAdd'])->name('users.search');

    Route::post('/message-sent', [MessageController::class, 'messageSent'])->name('message-sent');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/profil', [MessageController::class, 'profil'])->name('profil');
    Route::get('/parametres', [AuthController::class, 'settings'])->name('settings');
});

// Protégé par admin
Route::group(['middleware' => ['auth', 'verified', 'admin']], function () {
    Route::get('/admin', [AdminController::class, 'admin'])->name('admin');
});

// Debug helper: return authenticated user (temporary)
// Debug helper: show current session id and received cookies
Route::get('/__session', function () {
    $user = auth()->user();
    return response()->json([
        'session_id' => session()->getId(),
        'cookies' => request()->cookies->all(),
        'authenticated' => (bool) $user,
        'user_id' => $user->id ?? null,
    ]);
});

// Temporary debug route to wrap broadcasting auth with extra logging
Route::match(['get','post'], '/debug-broadcasting/auth', function (Request $request) {
    try {
        Log::info('debug broadcasting auth request', [
            'cookies' => $request->cookies->all(),
            'session_id' => $request->session()->getId(),
            'user_id' => optional($request->user())->id,
            'channel_name' => $request->input('channel_name'),
            'socket_id' => $request->input('socket_id'),
        ]);
    } catch (\Throwable $e) {
        Log::error('failed to log debug broadcasting auth', ['error' => $e->getMessage()]);
    }

    return app(\Illuminate\Broadcasting\BroadcastController::class)->authenticate($request);
});


Route::get('/__whoami', function () {
    $user = auth()->user();
    if (! $user) return response()->json(['authenticated' => false], 401);
    return response()->json(['authenticated' => true, 'id' => $user->id, 'email' => $user->email ?? null]);
});

// Debug helper: is authenticated user member of a conversation?
Route::get('/__is_member/{conversationId}', function ($conversationId) {
    $user = auth()->user();
    if (! $user) return response()->json(['authenticated' => false], 401);
    $isMember = $user->conversations()->where('conversations.id', $conversationId)->exists();
    return response()->json(['authenticated' => true, 'conversation_id' => (int) $conversationId, 'is_member' => (bool) $isMember]);
});