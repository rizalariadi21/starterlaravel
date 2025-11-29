<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('login_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 191)->nullable();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->string('ip', 45)->nullable();
            $table->boolean('success')->default(false);
            $table->string('message', 191)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_logs');
    }
};

