<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPengajuanTadSummarykandidat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_pengajuan_tad_summarykandidat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requirement_id')->nullable();
            $table->unsignedBigInteger('pengajuan_id')->nullable();
            $table->string('status')->default('new');
            $table->tinyInteger('accepted')->nullable();
            $table->dateTime('accepted_at')->nullable();
            $table->bigInteger('accepted_by')->nullable();
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
        Schema::dropIfExists('trans_pengajuan_tad_summarykandidat');
    }
}
