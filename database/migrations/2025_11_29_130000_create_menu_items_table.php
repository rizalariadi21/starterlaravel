<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('title', 191);
            $table->string('icon', 191)->nullable();
            $table->string('url', 255)->default('javascript:;');
            $table->string('route_name', 191)->nullable()->index();
            $table->boolean('caret')->default(false);
            $table->text('levels')->nullable(); // json array of allowed levels
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}

