<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransPenilaianVendorReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_penilaian_vendor_review', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penilaian_vendor_id')->nullable();
            $table->unsignedBigInteger('sign_by')->nullable();
            $table->dateTime('sign_at')->nullable();

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
        Schema::dropIfExists('trans_penilaian_vendor_review');
    }
}
