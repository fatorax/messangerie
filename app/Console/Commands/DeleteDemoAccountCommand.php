<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TestAccount;
use App\Mail\DemoAccountDeleted;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class DeleteDemoAccountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-demo-account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprime les comptes de démonstration plus vieux que 24 heures';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $this->info('La commande de suppression des comptes de démonstration commencée...');

            // Récupérer les comptes de test expirés
            $accounts = TestAccount::where('expires_at', '<', Carbon::now())->get();

            foreach ($accounts as $account) {
                // Suppression des utilisateurs associés, conversations et messages
                if ($account->user1) {
                    $account->user1->conversations()->delete();
                    $account->user1->messages()->delete();
                    $account->user1->delete();
                }
                if ($account->user2) {
                    $account->user2->conversations()->delete();
                    $account->user2->messages()->delete();
                    $account->user2->delete();
                }

                // Affichage dans la console
                $this->info("Suppression du compte de test : {$account->username1}, {$account->username2}");

                // Envoie de mail
                if ($account->requester_email) {
                    Mail::to($account->requester_email)->send(new DemoAccountDeleted(
                        $account->username1,
                        $account->username2
                    ));
                    $this->info("Email envoyé à : {$account->requester_email}");
                }

                // Suppression de l'enregistrement du compte de test
                $account->delete();
            }

            $this->info('La commande de suppression des comptes de démonstration a été exécutée avec succès !');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('La commande de suppression des comptes de démonstration a échouée !');
            $this->error($e->getMessage());
            Log::error($e);
            return Command::FAILURE;
        }
    }
}
