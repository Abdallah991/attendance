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
            $table->increments('id');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('nationality');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('supportedByTamkeen');
            $table->string('gender');
            $table->string('phone');
            $table->string('fcmToken')->nullable();
            $table->timestamp('dob');
            $table->unsignedInteger('cohortId');
            $table->foreign('cohortId')
                ->references('id')
                ->on('cohorts')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('student_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('studentId');
            $table->dateTime('log');
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
