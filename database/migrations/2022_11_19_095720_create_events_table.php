<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->unsignedInteger('capacity')->nullable();
            $table->timestamp('date');
            $table->string('time');
            $table->string('location');
            $table->string('status')->nullable();
            $table->string('type')->nullable();
            // ? multiple images
            $table->binary('image');
            // ? multiple hosts
            $table->string('host')->nullable();
            // ? multiple guests
            $table->string('guest')->nullable();
            // TODO: add students relationship MANY-MANY
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
