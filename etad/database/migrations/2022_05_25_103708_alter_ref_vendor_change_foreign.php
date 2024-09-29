<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRefVendorChangeForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'ref_vendor',
            function (Blueprint $table) {
                $table->dropForeign(['kategori_id']);
                $table->foreign('kategori_id')
                    ->references('id')
                    ->on('ref_kategori_vendor')
                    ->change();
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
        Schema::table(
            'ref_vendor',
            function (Blueprint $table) {
                $table->dropForeign(['kategori_id']);
                $table->foreign('kategori_id')
                    ->references('id')
                    ->on('ref_vendor')
                    ->change();
            }
        );
    }
}
