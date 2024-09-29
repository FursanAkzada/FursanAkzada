<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTransPengajuanTadDetailwawancaraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_pengajuan_tad_detailwawancara', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('summary_id')->nullable();
            $table->unsignedBigInteger('pertanyaan_id')->nullable();
            $table->integer('value')->default(0)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trans_pengajuan_tad_detailwawancara');
    }
}
