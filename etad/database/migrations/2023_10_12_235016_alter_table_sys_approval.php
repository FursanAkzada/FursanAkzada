<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableSysApproval extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table(
            'sys_approval',
            function (Blueprint $table) {
                $table->boolean('is_upgrade')->after('keterangan')->default(false);
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
        //
        Schema::table(
            'sys_approval',
            function (Blueprint $table) {
                $table->dropColumn('is_upgrade');
            }
        );
    }
}
