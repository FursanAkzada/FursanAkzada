<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransWawancaraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_pengajuan_tad_wawancara', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kandidat_id')->nullable();
            $table->tinyInteger('kesimpulan')->nullable();
            $table->text('saran')->nullable();

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('kandidat_id')->references('id')->on('trans_pengajuan_tad_kandidat');
        });
        Schema::create('trans_pengajuan_tad_wawancara_penilaian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wawancara_id')->nullable();
            $table->unsignedBigInteger('pertanyaan_id')->nullable();
            $table->integer('value')->unsigned()->nullable();

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('wawancara_id')->references('id')->on('trans_pengajuan_tad_wawancara')->onDelete('cascade');
            $table->foreign('pertanyaan_id')->references('id')->on('ref_wawancara_pertanyaan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trans_pengajuan_tad_wawancara_penilaian');
        Schema::dropIfExists('trans_pengajuan_tad_wawancara');
    }
}
