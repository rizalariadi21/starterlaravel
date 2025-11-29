<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table', 191);
            $table->string('model', 191);
            $table->string('primary_key', 191);
            $table->string('action', 50);
            $table->json('changes_before')->nullable();
            $table->json('changes_after')->nullable();
            $table->unsignedInteger('actor_id')->nullable()->index();
            $table->string('actor_type', 50)->nullable();
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('url', 255)->nullable();
            $table->timestamps();
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

