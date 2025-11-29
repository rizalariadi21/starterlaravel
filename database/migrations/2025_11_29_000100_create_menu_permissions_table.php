<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('menu_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('route_name', 191)->unique();
            $table->json('allowed_levels')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('menu_permissions');
    }
};