<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanMutasiRusmen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_pengajuan_mutasi',
            function (Blueprint $table) {
                // $table->string('no_tiket', 32)->after('status')->nullable();
                $table->unsignedBigInteger('unit_kerja_asal')
                    ->nullable();
                $table->unsignedBigInteger('unit_kerja_tujuan')
                    ->after('unit_kerja_asal')->nullable();
                $table->string('tgl_mutasi', 32)
                    ->after('unit_kerja_tujuan')->nullable();

                $table->dropForeign(['tad_id']);
                $table->dropForeign(['before_jabatan_id']);
                $table->dropForeign(['after_jabatan_id']);
                // $table->dropForeign(['before_cabang_id']);
                // $table->dropForeign(['after_cabang_id']);
                $table->dropColumn(
                    [
                        'tad_id',
                        'before_jabatan_id',
                        'after_jabatan_id',
                        'before_cabang_id',
                        'after_cabang_id'
                    ]
                );
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
            'trans_pengajuan_mutasi',
            function (Blueprint $table) {
                $table->dropColumn(
                    [
                        // 'no_tiket',
                        'unit_kerja_asal',
                        'unit_kerja_tujuan',
                        'tgl_mutasi',
                    ]
                );

                $table->unsignedBigInteger('tad_id')->nullable();
                $table->unsignedBigInteger('before_jabatan_id')->nullable();
                $table->unsignedBigInteger('after_jabatan_id')->nullable();
                $table->string('before_cabang_id')->nullable();
                $table->string('after_cabang_id')->nullable();

                $table->foreign('tad_id')->references('id')->on('ref_tad');
                $table->foreign('before_jabatan_id')->references('idunit')->on('ref_ehc_unit_kerja');
                $table->foreign('after_jabatan_id')->references('idunit')->on('ref_ehc_unit_kerja');
                // $table->foreign('before_cabang_id')->references('Sandi')->on('ref_ehc_divisi');
                // $table->foreign('after_cabang_id')->references('Sandi')->on('ref_ehc_divisi');
            }
        );
    }
}
