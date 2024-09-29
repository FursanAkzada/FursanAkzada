<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefTadKeluargaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_tipe_keluarga', function (Blueprint $table) {
            $table->id();
            $table->string('tipe')->nullable();

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('ref_tad_keluarga', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tad_id')->nullable();
            $table->unsignedBigInteger('tipe_id')->nullable();
            $table->string('nama')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->unsignedBigInteger('agama_id')->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->string('urutan_anak')->nullable();

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('tad_id')->references('id')->on('ref_tad')->onDelete('cascade');
            $table->foreign('tipe_id')->references('id')->on('ref_tipe_keluarga');
            $table->foreign('agama_id')->references('Sandi')->on('ref_ehc_agama');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_tipe_keluarga');
        Schema::dropIfExists('ref_tad_keluarga');
    }
}
