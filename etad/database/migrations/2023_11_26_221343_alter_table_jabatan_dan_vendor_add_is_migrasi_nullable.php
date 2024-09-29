<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableJabatanDanVendorAddIsMigrasiNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table(
            'ref_vendor',
            function (Blueprint $table) {
                $table->tinyInteger('is_migrasi')->nullable();
            }
        );

        Schema::table(
            'ref_ehc_unit_kerja',
            function (Blueprint $table) {
                $table->tinyInteger('is_migrasi')->nullable();
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
        //
        Schema::table(
            'ref_vendor',
            function (Blueprint $table) {
                $table->dropColumn('is_migrasi');
            }
        );

        Schema::table(
            'ref_ehc_unit_kerja',
            function (Blueprint $table) {
                $table->dropColumn('is_migrasi');
            }
        );
    }
}
