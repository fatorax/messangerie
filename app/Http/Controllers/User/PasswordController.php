<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    // Affiche le formulaire de changement de mot de passe
    public function edit()
    {
        return view('user.change_password');
    }

    // Met à jour le mot de passe
    public function update(Request $request)
    {
        $user = Auth::user();

        try {
            $validated = $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('settings')
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erreur de validation. Veuillez corriger les champs.');
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->route('settings')->with('success', 'Mot de passe mis à jour avec succès.');
    }
}
