<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanMutasiCc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_pengajuan_mutasi_cc',
            function (Blueprint $table) {
                $table->dropForeign(['pengajuan_id']);
                $table->foreign('pengajuan_id')
                    ->references('id')
                    ->on('trans_pengajuan_mutasi')
                    ->onDelete('cascade');
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
            'trans_pengajuan_mutasi_cc',
            function (Blueprint $table) {
                $table->dropForeign(['pengajuan_id']);
                $table->foreign('pengajuan_id')
                    ->references('id')
                    ->on('trans_pengajuan_tad')
                    ->onDelete('cascade');
            }
        );
    }
}
