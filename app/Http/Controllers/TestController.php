<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Models\User;
use App\Models\TestAccount;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function store()
    {
        return view('test');
    }

    public function send(Request $request)
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
                    'username' => $existingRequest->username1,
                    'password' => $existingRequest->password1,
                ],
                [
                    'username' => $existingRequest->username2,
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
            $tmpName1 = 'TestEnvoi' . rand(1, 10000);
        } while (User::where('username', $tmpName1)->exists());

        do {
            $tmpName2 = 'TestEnvoi' . rand(1, 10000);
        } while ($tmpName2 === $tmpName1 || User::where('username', $tmpName2)->exists());

        $password1 = Str::random(10);
        $password2 = Str::random(10);

        $email1 = $tmpName1 . '@test.com';
        $email2 = $tmpName2 . '@test.com';

        $user1 = User::create([
            'firstname' => $tmpName1,
            'lastname' => $tmpName1,
            'username' => $tmpName1,
            'email' => $email1,
            'avatar' => 'default.webp',
            'password' => Hash::make($password1),
            'email_verified_at' => now(),
            'verify_token' => null,
            'role' => 'test',
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
            'role' => 'test',
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
                'username' => $tmpName1,
                'password' => $password1,
            ],
            [
                'username' => $tmpName2,
                'password' => $password2,
            ]
        ];

        Mail::to($requesterEmail)->send(new TestMail($users));

        return redirect()->route('login')->with('success', 'Les informations de test ont été envoyées à votre adresse email.');
    }
}
