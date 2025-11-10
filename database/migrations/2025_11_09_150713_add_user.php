<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->after('id')->unique();
            $table->string('firstname')->after('username');
            $table->string('lastname')->after('firstname');
            $table->dropColumn('name');
            $table->string('avatar')->nullable()->after('email');
            $table->string('verify_token')->default(null)->after('remember_token')->nullable();
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->dropColumn('username');
            $table->dropColumn('surname');
            $table->dropColumn('firstname');
            $table->dropColumn('avatar');
        });
    }
};
