<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderOptionsJoinTable extends Migration
{
    public function up()
    {
        Schema::create('order_options', function (Blueprint $table) {
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('option_id');
            $table->decimal('price')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
            $table->index(['order_id', 'option_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_options');
    }
}
