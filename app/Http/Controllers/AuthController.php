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
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            return redirect()->route('login')->with('error', 'Cet email est déjà utilisé')->withInput();
        }

        $verifyToken = Str::uuid()->toString();

        $profilePicturePath = 'default.webp';
        if ($request->hasFile('profile-picture')) {
            $file = $request->file('profile-picture');
            if ($file && $file->isValid()) {
                $filename = $request->pseudonyme . '.' . $file->getClientOriginalExtension();
                $content = file_get_contents($file->getRealPath() ?: $file->getPathname());
                if ($content !== false) {
                    Storage::disk('public')->put('users/' . $filename, $content);
                    $profilePicturePath = $filename;
                }
            }
        }

        $user = User::create([
            'firstname' => $request->firtName,
            'lastname' => $request->lastName,
            'username' => $request->pseudonyme,
            'email' => $request->email,
            'avatar' => $profilePicturePath,
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
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(LoginRequest $request)
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

    public function logout()
    {
        Auth::logout();

        return redirect()->route('index');
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
                ['email' => $user->email],
                [
                    'token' => $token,
                    'created_at' => now(),
                ]
            );
            Mail::to($user->email)->send(new ForgotPassword($user, $token));
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
}
