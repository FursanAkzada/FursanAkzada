<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanResignPegawaiAddKepegawaianIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_pengajuan_resign_pegawai',
            function (Blueprint $table) {
                $table->unsignedBigInteger('kepegawaian_id')->after('tad_id');
                $table->foreign('kepegawaian_id')
                    ->references('id')
                    ->on('ref_tad_kepegawaian');
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
            'trans_pengajuan_resign_pegawai',
            function (Blueprint $table) {
                $table->dropForeign(['kepegawaian_id']);
                $table->dropColumn('kepegawaian_id');
            }
        );
    }
}
