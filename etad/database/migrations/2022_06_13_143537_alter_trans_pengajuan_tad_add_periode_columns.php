<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanTadAddPeriodeColumns extends Migration
{
    public function up()
    {
        Schema::table(
            'trans_pengajuan_tad',
            function (Blueprint $table) {
                $table->year('year')->after('surat_filepath');
                $table->string('semester', 4)->after('year');
            }
        );
    }

    public function down()
    {
        Schema::table(
            'trans_pengajuan_tad',
            function (Blueprint $table) {
                $table->dropColumn('year');
                $table->dropColumn('semester');
            }
        );
    }
}
