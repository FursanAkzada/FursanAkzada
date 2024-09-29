<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPengajuanTadDetailSummaryKandidat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_pengajuan_tad_detailsummarykandidat', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('summary_id')->nullable();
            $table->unsignedBigInteger('tad_id')->nullable();
            $table->string('status')->default('open');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('trans_pengajuan_tad_detailsummarykandidat');
    }
}
