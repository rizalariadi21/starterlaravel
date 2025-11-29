<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable()->index();
            $table->string('title', 191);
            $table->string('icon', 191)->nullable();
            $table->string('url', 255)->default('javascript:;');
            $table->string('route_name', 191)->nullable()->index();
            $table->boolean('caret')->default(false);
            $table->json('levels')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};

