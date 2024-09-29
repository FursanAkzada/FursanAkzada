<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPengajuanTadPewawancara extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'trans_pengajuan_tad_pewawancara',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('wawancara_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamps();
                $table->foreign('wawancara_id')->references('id')->on('trans_pengajuan_tad_wawancara');
                $table->foreign('user_id')->references('id')->on('sys_users');
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
        Schema::dropIfExists('trans_pengajuan_tad_pewawancara');
    }
}
