<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefWawancaraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_wawancara_kompetensi', function (Blueprint $table) {
            $table->id();
            $table->string('kompetensi')->nullable();
            $table->text('uraian')->nullable();

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('ref_wawancara_pertanyaan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kompetensi_id')->nullable();
            $table->string('pertanyaan')->nullable();
            
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('kompetensi_id')->references('id')->on('ref_wawancara_kompetensi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_wawancara_pertanyaan');
        Schema::dropIfExists('ref_wawancara_kompetensi');
    }
}
