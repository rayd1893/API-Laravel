<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigurationsTable extends Migration
{
    public function up()
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string('section');
            $table->string('country');
            $table->json('options');
            $table->timestamps();

            $table->unique(['section', 'country']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('configurations');
    }
}
