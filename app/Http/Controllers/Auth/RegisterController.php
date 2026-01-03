<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    // Affiche le formulaire d'inscription
    public function create()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    // Traite l'inscription
    public function store(RegisterRequest $request)
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
}
