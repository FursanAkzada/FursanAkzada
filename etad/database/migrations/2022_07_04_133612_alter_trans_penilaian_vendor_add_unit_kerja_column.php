<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPenilaianVendorAddUnitKerjaColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_penilaian_vendor',
            function (Blueprint $table) {
                $table->dropColumn('cabang_id');
                $table->unsignedBigInteger('unit_kerja_id')->after('id');
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
            'trans_penilaian_vendor',
            function (Blueprint $table) {
                $table->dropColumn('unit_kerja_type');
                $table->dropColumn('unit_kerja_id');
                $table->string('cabang_id');
            }
        );
    }
}
