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
        Schema::create('students', function (Blueprint $table) {
            // our students array
            $table->unsignedBigInteger('id')->primary();
            $table->string('platformId');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('gender');
            $table->string('nationality');
            $table->timestamp('dob');
            $table->string('acadamicQualification');
            $table->string('acadamicSpecialization');
            $table->string('scholarship');
            $table->string('password')->nullable();
            $table->string('supportedByTamkeen');
            $table->string('fcmToken')->nullable();
            $table->timestamps();
            //? linking a student to the cohort
            $table->unsignedInteger('cohortId');
            $table->foreign('cohortId')
                ->references('id')
                ->on('cohorts')
                ->onDelete('cascade');
        });

        // this is the transction date
        Schema::create('transactions', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedBigInteger('studentId');
            $table->timestamp('time');
            $table->string('area');
            $table->timestamps();
            $table->foreign('studentId')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');
            // ? TODO: might add users for their attendance 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
