<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('test_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('requester_email')->unique(); // Email du demandeur
            $table->foreignId('user1_id')->constrained('users')->onDelete('cascade');
            $table->string('username1');
            $table->string('password1'); // Mot de passe en clair pour renvoi
            $table->string('email1'); // Email de connexion
            $table->foreignId('user2_id')->constrained('users')->onDelete('cascade');
            $table->string('username2');
            $table->string('password2');
            $table->string('email2');
            $table->integer('resend_count')->default(0); // Nombre de renvois
            $table->timestamp('last_resend_at')->nullable(); // Dernier renvoi
            $table->timestamp('expires_at'); // Expiration des comptes (24h après création)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_accounts');
    }
};
