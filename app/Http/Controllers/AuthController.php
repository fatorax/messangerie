<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function logout()
    {
    }

    // public function motDePasseOublie()
    // {
    //     return view('auth.mot-de-passe-oublie');
    // }

    // public function reinitialiserMotDePasse()
    // {
    //     return view('auth.reinitialiser-mot-de-passe');
    // }

    // public function parametres()
    // {
    //     return view('auth.parametres');
    // }
}
