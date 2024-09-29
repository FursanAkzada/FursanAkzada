<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRefTadJabatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'ref_ehc_unit_kerja',
            function (Blueprint $table) {
                $table->unsignedBigInteger('kategori_id')
                    ->after('idunit')
                    ->nullable()
                    ->references('id')->on('ref_penilaian_tad');
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
            'ref_ehc_unit_kerja',
            function (Blueprint $table) {
                // $table->dropForeign(['kategori_id']);
                $table->dropColumn('kategori_id');
            }
        );
    }
}
