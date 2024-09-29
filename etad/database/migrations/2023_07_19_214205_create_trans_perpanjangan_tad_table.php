<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPerpanjanganTadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_perpanjangan_tad', function (Blueprint $table) {
            $table->id();
            $table->string('no_pengajuan')->nullable();
            $table->date('tgl_pengajuan')->nullable();
            $table->unsignedBigInteger('unit_kerja_id')->nullable();
            $table->unsignedBigInteger('tad_id')->nullable();
            $table->unsignedBigInteger('kepegawaian_id')->nullable();
            $table->unsignedBigInteger('to')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tgl_akhir_kontrak_lama')->nullable();
            $table->date('tgl_awal_kontrak_baru')->nullable();
            $table->date('tgl_akhir_kontrak_baru')->nullable();
            $table->string('status')->nullable();

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();

            // $table->foreign('unit_kerja_id')->references('id')->on('ref_org_structs`');
            // $table->foreign('tad_id')->references('id')->on('ref_tad');
            // $table->foreign('kepegawaian_id')
            //     ->references('id')
            //     ->on('ref_tad_kepegawaian');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trans_perpanjangan_tad');

    }
}
