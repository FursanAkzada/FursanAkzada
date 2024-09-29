<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RusmenCreateEhcDapegOutsourcingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('TBL_DAPEG_OUTSOURCING')) {
            Schema::create(
                'TBL_DAPEG_OUTSOURCING',
                function (Blueprint $table) {
                    $table->float('NO')->nullable();
                    $table->string('NAMA', 255)->nullable();
                    $table->string('NIO', 255)->primary();
                    $table->string('CABANG', 255)->nullable();
                    $table->string('UNIT_KERJA', 255)->nullable();
                    $table->string('JENIS_JABATAN', 255)->nullable();
                    $table->string('ALAMAT', 255)->nullable();
                    $table->string('KOTA', 255)->nullable();
                    $table->datetime('TGL_MASUK')->nullable();
                    $table->string('BLN_BERAKHIR', 50)->nullable();
                    $table->datetime('TGL_LAHIR')->nullable();
                    $table->string('TEMPAT_LAHIR', 255)->nullable();
                    $table->float('STAT_AKTIF')->nullable();
                    $table->string('JNS_KELAMIN', 20)->nullable();
                    $table->string('STAT_KAWIN', 40)->nullable();
                    $table->string('NAMA_PERUSAHAAN', 255)->nullable();
                    $table->string('NO_SK', 255)->nullable();
                    $table->string('PENDIDIKAN', 255)->nullable();
                    $table->string('REKENING', 255)->nullable();
                    $table->string('STKANTOR', 255)->nullable();
                    $table->datetime('TGL_PERSETUJUAN')->nullable();
                    $table->string('PENEMPATAN', 255)->nullable();
                    $table->string('KETERANGAN', 255)->nullable();
                }
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TBL_DAPEG_OUTSOURCING');
    }
}
