<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    // Affiche le formulaire de mot de passe oublié
    public function create()
    {
        return view('auth.forgot-password');
    }

    // Envoie l'email de réinitialisation
    public function store(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = Str::uuid()->toString();
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => $token,
                    'created_at' => now(),
                ]
            );
            Mail::to($user->email)->send(new ForgotPassword($user, $token));
            return redirect()->route('login')->with('success', 'Un email vous a été envoyé pour vous permettre de réinitialiser votre mot de passe');
        }

        return redirect()->route('forgot-password')->with('error', 'Aucun compte associé à cet email');
    }
}
