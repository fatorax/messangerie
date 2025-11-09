<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RGPDController;

Route::get('/', [AuthController::class, 'home'])->name('home');

Route::get('/inscription', [AuthController::class, 'inscription'])->name('inscription');
Route::get('/connexion', [AuthController::class, 'connexion'])->name('connexion');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/mot-de-passe-oublie', [AuthController::class, 'motDePasseOublie'])->name('mot-de-passe-oublie');
Route::get('/reinitialiser-mot-de-passe', [AuthController::class, 'reinitialiserMotDePasse'])->name('reinitialiser-mot-de-passe');

Route::get('/tableau-de-bord', [ChatController::class, 'tableauDeBord'])->name('tableau-de-bord');
Route::get('/profil', [ChatController::class, 'profil'])->name('profil');
Route::get('/parametres', [AuthController::class, 'parametres'])->name('parametres');

Route::get('/admin', [AdminController::class, 'admin'])->name('admin');

Route::get('/cgu', [RGPDController::class, 'cgu'])->name('cgu');
Route::get('/mentions-legales', [RGPDController::class, 'mentionsLegales'])->name('mentions-legales');
Route::get('/404', [RGPDController::class, 'page404'])->name('404');
