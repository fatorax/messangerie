<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // null pour chat privé
            $table->enum('type', ['global', 'group', 'private'])->default('group');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->boolean('is_encrypted')->default(false);
            $table->timestamps();
        });

        // Créer la conversation globale par défaut
        DB::table('conversations')->insert([
            'id' => 1,
            'name' => 'Général',
            'type' => 'global',
            'created_by' => null,
            'is_encrypted' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
