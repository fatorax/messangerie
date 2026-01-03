<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use App\Models\Conversation;
use App\Models\TestAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DemoAccountController extends Controller
{
    // Affiche le formulaire de demande de comptes de démo
    public function create()
    {
        return view('test');
    }

    // Crée les comptes de démo et envoie l'email
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $requesterEmail = $validated['email'];

        // Vérifier si cet email a déjà demandé des comptes de test
        $existingRequest = TestAccount::where('requester_email', $requesterEmail)->first();

        if ($existingRequest) {
            // Renvoyer les informations existantes
            $users = [
                [
                    'email' => $existingRequest->email1,
                    'password' => $existingRequest->password1,
                ],
                [
                    'email' => $existingRequest->email2,
                    'password' => $existingRequest->password2,
                ]
            ];

            // Mettre à jour le compteur de renvoi
            $existingRequest->update([
                'resend_count' => $existingRequest->resend_count + 1,
                'last_resend_at' => now(),
            ]);

            Mail::to($requesterEmail)->send(new TestMail($users));

            return redirect()->route('login')->with('info', 'Vous avez déjà demandé des comptes de test. Les informations vous ont été renvoyées par email.');
        }

        // Créer de nouveaux comptes de test
        do {
            $tmpName1 = 'User' . rand(1, 10000);
        } while (User::where('username', $tmpName1)->exists());

        do {
            $tmpName2 = 'User' . rand(1, 10000);
        } while ($tmpName2 === $tmpName1 || User::where('username', $tmpName2)->exists());

        $password1 = Str::random(10);
        $password2 = Str::random(10);

        $email1 = $tmpName1 . '@demo.com';
        $email2 = $tmpName2 . '@demo.com';

        $user1 = User::create([
            'firstname' => $tmpName1,
            'lastname' => $tmpName1,
            'username' => $tmpName1,
            'email' => $email1,
            'avatar' => 'default.webp',
            'password' => Hash::make($password1),
            'email_verified_at' => now(),
            'verify_token' => null,
            'role' => 'demo',
        ]);

        $user2 = User::create([
            'firstname' => $tmpName2,
            'lastname' => $tmpName2,
            'username' => $tmpName2,
            'email' => $email2,
            'avatar' => 'default.webp',
            'password' => Hash::make($password2),
            'email_verified_at' => now(),
            'verify_token' => null,
            'role' => 'demo',
        ]);

        // Créer une conversation entre les deux comptes de test
        $conversation = Conversation::create([
            'name' => null,
            'type' => 'private',
            'created_by' => $user1->id,
            'is_encrypted' => false,
        ]);

        // Ajouter les deux utilisateurs à la conversation
        $conversation->users()->attach([
            $user1->id => ['role' => 'member'],
            $user2->id => ['role' => 'member'],
        ]);

        // Enregistrer la demande dans la table test_accounts
        TestAccount::create([
            'requester_email' => $requesterEmail,
            'user1_id' => $user1->id,
            'username1' => $tmpName1,
            'password1' => $password1,
            'email1' => $email1,
            'user2_id' => $user2->id,
            'username2' => $tmpName2,
            'password2' => $password2,
            'email2' => $email2,
            'expires_at' => now()->addHours(TestAccount::LIFETIME_HOURS),
        ]);

        $users = [
            [
                'email' => $email1,
                'password' => $password1,
            ],
            [
                'email' => $email2,
                'password' => $password2,
            ]
        ];

        Mail::to($requesterEmail)->send(new TestMail($users));

        return redirect()->route('login')->with('success', 'Les informations de test ont été envoyées à votre adresse email.');
    }
}
