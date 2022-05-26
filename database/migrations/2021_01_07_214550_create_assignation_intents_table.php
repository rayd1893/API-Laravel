<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignationIntentsTable extends Migration
{
    public function up()
    {
        Schema::create('assignation_intents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->uuid('uuid')->unique();
            $table->foreignId('courier_id')->nullable();
            $table->string('status');
            $table->timestamp('take_until')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('courier_id')->references('id')->on('couriers');
        });
    }

    public function down()
    {
        Schema::dropIfExists('assignation_intents');
    }
}
