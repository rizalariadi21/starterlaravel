<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('table', 191);
            $table->string('model', 191)->nullable();
            $table->string('primary_key', 191)->nullable();
            $table->string('action', 20);
            $table->text('changes_before')->nullable();
            $table->text('changes_after')->nullable();
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('actor_type', 50)->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
