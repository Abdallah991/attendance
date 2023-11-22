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
        Schema::create('comments', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('platformId')->unique();
            $table->string('yanal')->nullable();
            $table->string('abdallah')->nullable();
            $table->string('ebrahim')->unique();
            $table->string('yaman')->nullable();
            $table->string('seham')->nullable();
            $table->string('donya')->nullable();
            $table->string('sarah')->nullable();
            $table->string('melvis')->nullable();
            $table->string('techAssistant1')->nullable();
            $table->string('techAssistant2')->nullable();
            $table->string('techAssistant3')->nullable();
            $table->string('techAssistant4')->nullable();
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
        //
    }
};
