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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('nationality');
            $table->string('cohort');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('supported_by_tamkeen');
            $table->string('gender');
            $table->string('phone');
            $table->string('fcm_token')->nullable();
            $table->timestamp('dob');
            $table->timestamps();
        });

        Schema::create('students_log', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_id');
            $table->dateTime('log');
            $table->timestamps();
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');
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
