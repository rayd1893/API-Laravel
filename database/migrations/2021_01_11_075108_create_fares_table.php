<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaresTable extends Migration
{
    public function up()
    {
        Schema::create('fares', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->json('price');
            $table->integer('distance');
            $table->integer('extra_distance');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fares');
    }
}
