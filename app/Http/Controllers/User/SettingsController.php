<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TestAccount;
use App\Models\FriendRequest;
use App\Mail\DemoAccountDeleted;
use App\Mail\AccountDeleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller
{
    // Affiche les paramètres
    public function index()
    {
        $user = Auth::user();
        return view('user.settings', compact('user'));
    }

    // Met à jour les paramètres
    public function update(Request $request)
    {
        $user = Auth::user();

        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'profile-picture' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,webp,svg',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('settings')
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erreur de validation. Veuillez corriger les champs.');
        }

        if ($request->hasFile('profile-picture')) {
            $file = $request->file('profile-picture');
            if ($file && $file->isValid()) {
                $filename = $request->username . '.' . $file->getClientOriginalExtension();
                $content = file_get_contents($file->getRealPath() ?: $file->getPathname());
                if ($content !== false) {
                    // supprimer l'ancien avatar
                    if ($user->avatar !== 'default.webp') {
                        Storage::disk('public')->delete('users/' . $user->avatar);
                    }
                    // ajouter le nouveau avatar
                    $user->avatar = $filename;
                    Storage::disk('public')->put('users/' . $filename, $content);
                }
            }
        }

        $user->username = $request->input('username');
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->save();

        return redirect()->route('settings')->with('success', 'Paramètres mis à jour avec succès.');
    }

    // Supprime le compte
    public function destroy()
    {
        $user = Auth::user();
        
        // Vérifier si c'est un compte démo
        $testAccount = TestAccount::where('user1_id', $user->id)
            ->orWhere('user2_id', $user->id)
            ->first();

        if ($testAccount) {
            // Supprimer l'autre utilisateur du compte démo
            $otherUser = $testAccount->user1_id == $user->id ? $testAccount->user2 : $testAccount->user1;
            
            if ($otherUser) {
                $otherUser->conversations()->delete();
                $otherUser->messages()->delete();
                
                if ($otherUser->avatar !== 'default.webp') {
                    Storage::disk('public')->delete('users/' . $otherUser->avatar);
                }
                $otherUser->delete();
            }

            // Envoyer l'email de notification
            if ($testAccount->requester_email) {
                Mail::to($testAccount->requester_email)->send(new DemoAccountDeleted(
                    $testAccount->username1,
                    $testAccount->username2
                ));
            }

            // Supprimer le TestAccount
            $testAccount->delete();
        } else {
            // Compte normal : supprimer les conversations privées et leurs messages
            $privateConversations = $user->conversations()->where('type', 'private')->get();
            foreach ($privateConversations as $conversation) {
                $conversation->messages()->delete();
                $conversation->users()->detach();
                $conversation->delete();
            }

            // Supprimer les demandes d'ami (envoyées et reçues)
            FriendRequest::where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->delete();

            // Supprimer les messages dans les channels (global)
            $user->messages()->delete();

            // Détacher l'utilisateur des channels
            $user->conversations()->where('type', 'global')->detach();

            // Supprimer les lectures de messages
            $user->readMessages()->detach();

            // Envoyer l'email de confirmation de suppression
            Mail::to($user->email)->send(new AccountDeleted($user->username, false));
        }

        if ($user->avatar !== 'default.webp') {
            Storage::disk('public')->delete('users/' . $user->avatar);
        }
        
        $user->delete();
        
        return redirect()->route('login')->with('success', 'Compte supprimé avec succès.');
    }
}
