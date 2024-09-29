<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPengajuanTadPenerimaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_pengajuan_tad_penerimaan', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('new');
            $table->unsignedBigInteger('wawancara_id')->nullable();
            $table->string('nio')->nullable();
            $table->string('no_sk')->nullable();
            $table->string('keputusan')->nullable()->comment('Diterima / Ditolak');
            $table->date('tgl_keputusan')->nullable();
            $table->string('penerimaan_filename')->nullable()->comment('File Penerimaan');
            $table->text('penerimaan_filepath')->nullable()->comment('File Penerimaan');
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
        Schema::dropIfExists('trans_pengajuan_tad_penerimaan');
    }
}
