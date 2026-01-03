<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

class VerifyEmailController extends Controller
{
    // Vérifie l'email de l'utilisateur
    public function __invoke($email, $hash)
    {
        $user = User::where('email', $email)->first();

        if ($user && $user->verify_token == $hash) {
            $user->verify_token = null;
            $user->email_verified_at = now();
            $user->save();
        }

        return redirect()->route('login')->with('success', 'Votre adresse email a bien été vérifiée');
    }
}
