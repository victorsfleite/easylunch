<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuOptionsPivotTable extends Migration
{
    public function up()
    {
        Schema::create('menu_options', function (Blueprint $table) {
            $table->unsignedInteger('menu_id');
            $table->unsignedInteger('option_id');
            $table->decimal('price')->nullable();
            $table->timestamps();

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
            $table->index(['menu_id', 'option_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_options');
    }
}
