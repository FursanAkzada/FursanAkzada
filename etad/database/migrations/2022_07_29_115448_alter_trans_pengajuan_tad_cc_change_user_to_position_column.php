<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanTadCcChangeUserToPositionColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_pengajuan_tad_cc',
            function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->unsignedBigInteger('user_id')
                    ->comment('ref_positions')
                    ->change();
                $table->foreign('user_id')->references('id')->on('ref_positions');
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
            'trans_pengajuan_tad_cc',
            function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->unsignedBigInteger('user_id')
                    ->comment('sys_users')
                    ->change();
                $table->foreign('user_id')->references('id')->on('sys_users');
            }
        );
    }
}
