<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTransPenilaianTadAndVendorAddTo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('trans_penilaian_tad', function (Blueprint $table) {
            $table->unsignedBigInteger('to')->nullable()->after('tad_id');
        });

        Schema::table('trans_penilaian_vendor', function (Blueprint $table) {
            $table->unsignedBigInteger('to')->nullable()->after('vendor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('trans_penilaian_tad', function (Blueprint $table) {
            $table->dropColumn(['to']);
        });

        Schema::table('trans_penilaian_vendor', function (Blueprint $table) {
            $table->dropColumn(['to']);
        });
    }
}
