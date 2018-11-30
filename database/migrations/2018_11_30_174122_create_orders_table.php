<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');

            $table->text('description');
            $table->unsignedInteger('owner_id')->nullable();
            $table->unsignedInteger('menu_id');
            $table->dateTime('completed_at')->nullable();

            $table->timestamps();

            $table->foreign('owner_id')
                ->references('id')->on('users')
                ->onDelete('set null');

            $table->foreign('menu_id')
                ->references('id')->on('menus')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
