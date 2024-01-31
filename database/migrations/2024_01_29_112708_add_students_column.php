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
        //
        Schema::table('students', function (Blueprint $table) {

            $table->text('profilePicture')->nullable();
            $table->text('cprPicture')->nullable();
            $table->boolean('pictureChanged')->default(0)->nullable();
            $table->boolean('cprChanged')->default(0)->nullable();
            $table->string('maritalStatus')->nullable();
            $table->string('highestDegree')->nullable();
            $table->string('academicInstitute')->nullable();
            $table->string('graduationDate')->nullable();
            $table->string('occupation')->nullable();
            $table->string('currentJobTitle')->nullable();
            $table->string('companyNameAndCR')->nullable();
            $table->integer('sp')->nullable();
            $table->string('sponsorship')->nullable();
            $table->boolean('unipal')->default(0)->nullable();
            $table->boolean('discord')->default(0)->nullable();
            $table->boolean('trainMe')->default(0)->nullable();
            $table->text('notes')->nullable();
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
        //

    }
};
