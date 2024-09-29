<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPenilaianVendorLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'trans_penilaian_vendor_logs',
            function (Blueprint $table) {
                $table->id();
                $table->string('status', 16);
                $table->unsignedBigInteger('penilaian_vendor_id');
                $table->string('keterangan', 256);
                $table->string('is_active', 1)->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();

                $table->foreign('penilaian_vendor_id', 'penilaian_vendor_id')
                    ->references('id')
                    ->on('trans_penilaian_vendor');
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
        Schema::dropIfExists('trans_penilaian_vendor_logs');
    }
}
