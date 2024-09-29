<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransPengajuanTadTlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('trans_pengajuan_tad_requirement_tl');
        Schema::create('trans_pengajuan_tad_tl', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id')->nullable();
            $table->string('no_surat')->nullable();
            $table->text('surat_filename')->nullable();
            $table->text('surat_filepath')->nullable();

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('pengajuan_id')->references('id')->on('trans_pengajuan_tad')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trans_pengajuan_tad_tl');
    }
}
