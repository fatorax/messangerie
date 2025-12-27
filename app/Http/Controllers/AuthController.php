<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use App\Mail\ForgotPassword;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            return redirect()->route('login')->with('error', 'Cet email est déjà utilisé')->withInput();
        }

        $verifyToken = Str::uuid()->toString();

        $user = User::create([
            'firstname' => $request->firtName,
            'lastname' => $request->lastName,
            'username' => $request->pseudonyme,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verify_token' => $verifyToken,
        ]);

        Mail::to($request->email)->send(new VerifyEmail($user));

        return redirect()->route('login')->with('success', 'Votre inscription a bien été prise en compte, merci de vérifier votre adresse email');
    }

    public function verifyEmail($email, $hash)
    {
        $user = User::where('email', $email)->first();

        if ($user->verify_token == $hash) {
            $user->verify_token = null;
            $user->email_verified_at = now();
            $user->save();
        }

        return redirect()->route('login')->with('success', 'Votre adresse email a bien été vérifiée');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        
        if ($user && $user->verify_token == $request->hash) {
            Auth::login($user);
            return redirect()->route('dashboard');
        }

        return redirect()->route('login');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $token = Str::uuid()->toString();
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email], // condition pour vérifier si ça existe
                [
                    'token' => $token,
                    'created_at' => now(),
                ]
            );
            Mail::to($user->email)->send(new ForgotPassword($user->email, $token));
            return redirect()->route('login')->with('success', 'Un email vous a été envoyé pour vous permettre de réinitialiser votre mot de passe');
        }
    }

    public function showResetPassword()
    {
        return view('auth.reset-password', ['email' => request()->email, 'token' => request()->token]);
    }

    public function resetPassword(ResetPasswordRequest $request)
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

    // public function parametres()
    // {
    //     return view('auth.parametres');
    // }
}
