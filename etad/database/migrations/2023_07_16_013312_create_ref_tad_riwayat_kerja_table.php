<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefTadRiwayatKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_tad_riwayat_kerja', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tad_id')->nullable();
            $table->string('title')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('company')->nullable();
            $table->text('location_company')->nullable();
            $table->string('system_working')->nullable();
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('ref_tad_riwayat_kerja');
    }
}
