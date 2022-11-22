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
        Schema::create('student_event', function (Blueprint $table) {
            $table->integer('studentId')->unsigned();
            $table->integer('eventId')->unsigned();
            $table->foreign('studentId')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');
            $table->foreign('eventId')
                ->references('id')
                ->on('events')
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
        Schema::dropIfExists('student_events');
    }
};
