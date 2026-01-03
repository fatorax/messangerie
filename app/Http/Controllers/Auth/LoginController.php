<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Affiche le formulaire de connexion
    public function create()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    // Traite la connexion
    public function store(LoginRequest $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $user = User::where('email', $request->email)->first();

        if ($user && $user->verify_token == $request->hash) {
            Auth::login($user);
            return redirect()->route('dashboard');
        }

        return redirect()->route('login')->with('error', 'Cet email ou mot de passe est invalide');
    }

    // Déconnexion
    public function destroy()
    {
        Auth::logout();
        return redirect()->route('index');
    }
}
