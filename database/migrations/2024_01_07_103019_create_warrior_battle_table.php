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
        Schema::create('battle_warrior', function (Blueprint $table) {
            $table->id();
            $table->string('warrior_id');
            $table->string('battle_id');
            $table->timestamps();
            $table->unique(['warrior_id', 'battle_id']);
            $table->foreign('warrior_id')->references('id')->on('warriors')->onDelete('cascade');
            $table->foreign('battle_id')->references('id')->on('battles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warrior_battle');
    }
};
