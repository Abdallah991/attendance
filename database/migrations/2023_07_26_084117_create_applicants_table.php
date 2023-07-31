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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('platformId')->unique();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->date('dob')->nullable();
            $table->string('acadamicQualification')->nullable();
            $table->string('acadamicSpecialization')->nullable();
            $table->string('status');
            $table->string('employment');
            $table->string('howDidYouHear');
            $table->json('progresses')->nullable();
            // $table->integer('score');
            $table->float('score', 8, 2);
            $table->string('lastGameDate');
            $table->string('updatedBy')->nullable();
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
        Schema::dropIfExists('applicants');

        // ! Applicants
        // Schema::table('applicants', function (Blueprint $table) {
        //     $table->integer('score')->change();
        // });
    }
};
