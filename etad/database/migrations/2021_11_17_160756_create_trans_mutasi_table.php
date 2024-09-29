<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransMutasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'trans_pengajuan_mutasi',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tad_id')->nullable();
                $table->unsignedBigInteger('before_jabatan_id')->nullable();
                $table->unsignedBigInteger('after_jabatan_id')->nullable();
                $table->string('before_cabang_id')->nullable();
                $table->string('after_cabang_id')->nullable();
                $table->integer('jenis_mutasi')->nullable();

                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('tad_id')->references('id')->on('ref_tad');
                $table->foreign('before_jabatan_id')->references('idunit')->on('ref_ehc_unit_kerja');
                $table->foreign('after_jabatan_id')->references('idunit')->on('ref_ehc_unit_kerja');
                $table->foreign('before_cabang_id')->references('Sandi')->on('ref_ehc_divisi');
                $table->foreign('after_cabang_id')->references('Sandi')->on('ref_ehc_divisi');
            }
        );

        Schema::create(
            'trans_pengajuan_mutasi_logs',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('pengajuan_id')->nullable();
                $table->string('keterangan')->nullable()->default('text');
                $table->string('status')->nullable()->default('text');
                $table->tinyInteger('is_active')->default(1);

                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('pengajuan_id')->references('id')->on('trans_pengajuan_mutasi')->onDelete('cascade');
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
        Schema::dropIfExists('trans_pengajuan_mutasi_logs');
        Schema::dropIfExists('trans_pengajuan_mutasi');
    }
}
