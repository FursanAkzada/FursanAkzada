<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRefTadTableAddPositionId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_tad', function (Blueprint $table) {
            $table->unsignedBigInteger('jabatan_id')->nullable();
            // $table->foreign('jabatan_id')->references('idunit')->on('TBL_UNITKERJA');
        });
        Schema::table('ref_tad_failed', function (Blueprint $table) {
            $table->unsignedBigInteger('jabatan_id')->nullable();
            // $table->foreign('jabatan_id')->references('idunit')->on('TBL_UNITKERJA');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_tad', function (Blueprint $table) {
            // $table->dropForeign(['jabatan_id']);
            $table->dropColumn('jabatan_id');
        });
        Schema::table('ref_tad_failed', function (Blueprint $table) {
            // $table->dropForeign(['jabatan_id']);
            $table->dropColumn('jabatan_id');
        });
    }
}
