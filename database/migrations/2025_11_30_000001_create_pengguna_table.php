<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->increments('id_pengguna');
            $table->string('pengguna', 191);
            $table->string('institusi', 191)->nullable();
            $table->string('no_hp', 50)->nullable();
            $table->string('username', 191)->unique();
            $table->string('password', 191);
            $table->unsignedTinyInteger('level')->default(1);
            $table->unsignedTinyInteger('status')->default(1);
            $table->string('api_token', 191)->nullable()->unique();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};

