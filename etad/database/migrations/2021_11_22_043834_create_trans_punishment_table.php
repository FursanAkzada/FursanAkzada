<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransPunishmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_punishment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tad_id')->nullable();
            $table->string('sk')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->text('eviden')->nullable();
            $table->bigInteger('jenis_id')->nullable();

            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();

            $table->foreign('tad_id')->references('id')->on('ref_tad');
            // $table->foreign('jenis_id')->references('sandi')->on('Jenis_Reward');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trans_punishment');
    }
}
