<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanResignAddUnitKerjaColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_pengajuan_resign',
            function (Blueprint $table) {
                $table->dropForeign(['so_id']);
                $table->dropColumn(['so_id']);
                $table->unsignedBigInteger('unit_kerja_id')
                    ->after('status')->nullable();
                $table->string('unit_kerja_type')
                    ->after('unit_kerja_id')->nullable();
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
            'trans_pengajuan_resign',
            function (Blueprint $table) {
                $table->dropColumn('unit_kerja_type');
                $table->dropColumn('unit_kerja_id');
                $table->unsignedBigInteger('so_id')->after('status')->nullable();
                $table->foreign('so_id')->references('id')->on('ref_org_structs');
            }
        );
    }
}
