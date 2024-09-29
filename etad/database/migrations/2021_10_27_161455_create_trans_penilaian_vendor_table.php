<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransPenilaianVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_penilaian_vendor', function (Blueprint $table) {
            $table->id();
            $table->string('cabang_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->integer('tahun')->nullable();
            $table->integer('jumlah_tad')->nullable();
            $table->text('kesimpulan')->nullable();
            $table->text('kritik')->nullable();
            $table->text('saran')->nullable();
            
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('ref_vendor');
        });

        Schema::create('trans_penilaian_vendor_jawaban', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penilaian_vendor_id')->nullable();
            $table->unsignedBigInteger('pertanyaan_id')->nullable();
            $table->integer('value')->nullable();

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('penilaian_vendor_id')->references('id')->on('trans_penilaian_vendor')->onDelete('cascade');
            $table->foreign('pertanyaan_id')->references('id')->on('ref_penilaian_vendor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trans_penilaian_vendor');
        Schema::dropIfExists('trans_penilaian_vendor_jawaban');
    }
}
