<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function settings()
    {
        $user = Auth::user();
        return view('user.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('settings')
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erreur de validation. Veuillez corriger les champs.');
        }

        $user->username = $request->input('username');
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->save();

        return redirect()->route('settings')->with('success', 'Paramètres mis à jour avec succès.');
    }

    public function changePassword()
    {
        return view('user.change_password');
    }

    public function updatePassword(Request $request)
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

    public function deleteAccount()
    {
        // $user = Auth::user();
        // $user->delete();
        // return redirect()->route('login')->with('success', 'Compte supprimé avec succès.');
    }
}
