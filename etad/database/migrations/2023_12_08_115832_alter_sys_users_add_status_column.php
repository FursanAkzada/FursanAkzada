<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSysUsersAddStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'sys_users',
            function (Blueprint $table) {
                $table->string('status', 16)->default('active');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'sys_users',
            function (Blueprint $table) {
                $table->dropColumn('status');
            }
        );
    }
}
