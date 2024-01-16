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
        // add columns for tables
        Schema::table('students', function (Blueprint $table) {
            $table->string('progressAt')->nullable();
            $table->integer('AuditGiven')->nullable();
            $table->integer('AuditReceived')->nullable();
            $table->float('userAuditRatio')->nullable();
            $table->integer('level')->nullable();
            $table->string('auditDate')->nullable();
            $table->string('auditReceivedDate')->nullable();
            $table->string('auditGivenDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // remove columns from table
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('progressAt');
            $table->dropColumn('AuditGiven');
            $table->dropColumn('AuditReceived');
            $table->dropColumn('userAuditRatio');
            $table->dropColumn('level');
            $table->dropColumn('auditDate');
            $table->dropColumn('auditReceivedDate');
            $table->dropColumn('auditGivenDate');
        });
    }
};
