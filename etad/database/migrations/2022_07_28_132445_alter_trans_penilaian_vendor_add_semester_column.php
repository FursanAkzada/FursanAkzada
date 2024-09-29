<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPenilaianVendorAddSemesterColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_penilaian_vendor',
            function (Blueprint $table) {
                $table->string('semester', 4)->after('tahun');
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
            'trans_penilaian_vendor',
            function (Blueprint $table) {
                $table->dropColumn('semester');
            }
        );
    }
}
