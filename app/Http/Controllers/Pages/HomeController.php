<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    // Affiche la page d'accueil
    public function index()
    {
        return view('index');
    }
}
