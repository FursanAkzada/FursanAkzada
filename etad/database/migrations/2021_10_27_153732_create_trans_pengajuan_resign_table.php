<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPengajuanResignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'trans_pengajuan_resign',
            function (Blueprint $table) {
                $table->id();
                $table->string('no_tiket')->nullable();
                $table->string('cabang_id')->nullable();
                $table->string('surat_filename')->nullable();
                $table->text('surat_filepath')->nullable();

                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('cabang_id')->references('Sandi')->on('ref_ehc_divisi');
            }
        );

        Schema::create(
            'trans_pengajuan_resign_pegawai',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('pengajuan_id')->nullable();
                $table->unsignedBigInteger('tad_id')->nullable();
                $table->date('tanggal_resign')->nullable();
                $table->date('tanggal_efektif')->nullable();

                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('pengajuan_id')->references('id')->on('trans_pengajuan_resign')->cascadeOnDelete();
                $table->foreign('tad_id')->references('id')->on('ref_tad');
            }
        );

        Schema::create(
            'trans_pengajuan_resign_logs',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('pengajuan_id')->nullable();
                $table->string('keterangan')->nullable()->default('text');
                $table->string('status')->nullable()->default('text');
                $table->tinyInteger('is_active')->default(1);

                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('pengajuan_id')->references('id')->on('trans_pengajuan_resign')->cascadeOnDelete();
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
        Schema::dropIfExists('trans_pengajuan_resign_logs');
        Schema::dropIfExists('trans_pengajuan_resign_pegawai');
        Schema::dropIfExists('trans_pengajuan_resign');
    }
}
