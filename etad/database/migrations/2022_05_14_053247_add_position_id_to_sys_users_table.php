<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPositionIdToSysUsersTable extends Migration
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
                $table->unsignedBigInteger('position_id')->nullable()->after('id');
                $table->foreign('position_id')->references('id')->on('ref_positions');
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
                $table->dropForeign(['position_id']);
                $table->dropColumn('position_id');
            }
        );
    }
}
