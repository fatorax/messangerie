<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    // Affiche le formulaire de réinitialisation
    public function edit()
    {
        return view('auth.reset-password', ['email' => request()->email, 'token' => request()->token]);
    }

    // Traite la réinitialisation du mot de passe
    public function update(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $token = DB::table('password_reset_tokens')->where('email', $user->email)->where('token', $request->token)->first();
            if ($token) {
                $user->password = Hash::make($request->password);
                $user->save();
                return redirect()->route('login')->with('success', 'Votre mot de passe a bien été modifié');
            }
        }

        return redirect()->route('reset-password')->with('error', 'Ce lien de réinitialisation de mot de passe est invalide');
    }
}
