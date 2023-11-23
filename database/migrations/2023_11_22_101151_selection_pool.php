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
        Schema::create('sp', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('platformId')->unique();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('profilePicture')->nullable();
            $table->string('cprPicture')->nullable();
            $table->boolean('pictureChanged')->default(0)->nullable();
            $table->date('dob')->nullable();
            $table->string('acadamicQualification')->nullable();
            $table->string('acadamicSpecialization')->nullable();
            $table->string('employment');
            $table->string('howDidYouHear');
            $table->string('sp');
            $table->json('progresses')->nullable();
            $table->json('registrations')->nullable();
            $table->string('lastActivity')->nullable();
            $table->bigInteger('xp')->nullable();
            $table->integer('level')->nullable();
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
