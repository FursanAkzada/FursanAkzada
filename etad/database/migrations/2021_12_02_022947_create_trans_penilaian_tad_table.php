<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPenilaianTadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'trans_penilaian_tad',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tad_id')->nullable();
                $table->unsignedBigInteger('kepegawaian_id')
                    ->nullable()
                    ->unique();
                $table->string('masa_kerja')->nullable();
                $table->string('periode_penilaian')->nullable();
                $table->string('tahun')->nullable();
                $table->tinyInteger('masa_kontrak');

                $table->text('prestasi')->nullable();
                $table->text('indisipliner')->nullable();
                $table->text('saran')->nullable();
                $table->text('status_perpanjangan')->nullable();

                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();

                // $table->foreign('tad_id')->references('id')->on('ref_tad');
                // $table->foreign('kepegawaian_id')
                //         ->references('id')
                //         ->on('ref_tad_kepegawaian');
            }
        );

        Schema::create(
            'trans_penilaian_tad_jawaban',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('penilaian_id')->nullable();
                $table->unsignedBigInteger('pertanyaan_id')->nullable();
                $table->unsignedInteger('value')->unsigned()->nullable();

                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('penilaian_id')->references('id')->on('trans_penilaian_tad')->onDelete('cascade');
                $table->foreign('pertanyaan_id')->references('id')->on('ref_penilaian_tad');
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
        Schema::dropIfExists('trans_penilaian_tad_jawaban');
        Schema::dropIfExists('trans_penilaian_tad');
    }
}
