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
        Schema::create('warriors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('oldScore')->nullable();
            $table->integer('newScore')->nullable();
            $table->string('platformId');
            $table->string('codeWarsId');
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
        Schema::dropIfExists('warriors');
    }
};
