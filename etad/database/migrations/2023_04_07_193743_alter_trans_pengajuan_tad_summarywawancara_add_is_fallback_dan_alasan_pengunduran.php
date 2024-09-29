<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanTadSummarywawancaraAddIsFallbackDanAlasanPengunduran extends Migration
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
            'trans_pengajuan_tad_summarywawancara',
            function (Blueprint $table) {
                $table->boolean('is_fallback')->default(0)->after('tgl_wawancara');
                $table->text('alasan_pengunduran')->nullable()->after('is_fallback');
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
            'trans_pengajuan_tad_summarywawancara',
            function (Blueprint $table) {
                $table->dropColumn(['is_fallback']);
                $table->dropColumn(['alasan_pengunduran']);
            }
        );
    }
}
