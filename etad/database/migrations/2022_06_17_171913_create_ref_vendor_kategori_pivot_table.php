<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefVendorKategoriPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'ref_vendor_kategori_pivot',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('vendor_id');
                $table->unsignedBigInteger('kategori_id');

                $table->unique(['vendor_id', 'kategori_id']);
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('ref_vendor_kategori_pivot');
    }
}
