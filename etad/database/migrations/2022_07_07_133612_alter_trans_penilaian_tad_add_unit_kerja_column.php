<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPenilaianTadAddUnitKerjaColumn extends Migration
{
    public function up()
    {
        Schema::table(
            'trans_penilaian_tad',
            function (Blueprint $table) {
                $table->unsignedBigInteger('unit_kerja_id')->after('status');
                $table->string('unit_kerja_type', 64)->after('unit_kerja_id');
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
            'trans_penilaian_tad',
            function (Blueprint $table) {
                $table->dropColumn('unit_kerja_type');
                $table->dropColumn('unit_kerja_id');
            }
        );
    }
}
