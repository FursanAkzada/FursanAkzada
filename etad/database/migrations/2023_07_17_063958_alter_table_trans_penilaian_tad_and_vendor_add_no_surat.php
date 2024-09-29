<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTransPenilaianTadAndVendorAddNoSurat extends Migration
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
            $table->string('no_surat')->nullable()->after('tad_id');
        });

        Schema::table('trans_penilaian_vendor', function (Blueprint $table) {
            $table->string('no_surat')->nullable()->after('vendor_id');
        });

        Schema::table('trans_pembinaan', function (Blueprint $table) {
            $table->unsignedBigInteger('to')->nullable()->after('sk');
            $table->string('no_surat')->nullable()->after('sk');
        });

        Schema::table('trans_reward', function (Blueprint $table) {
            $table->unsignedBigInteger('to')->nullable()->after('sk');
            $table->string('no_surat')->nullable()->after('sk');
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
            $table->dropColumn(['no_surat']);
        });

        Schema::table('trans_penilaian_vendor', function (Blueprint $table) {
            $table->dropColumn(['no_surat']);
        });

        Schema::table('trans_pembinaan', function (Blueprint $table) {
            $table->dropColumn(['no_surat']);
            $table->dropColumn(['to']);
        });

        Schema::table('trans_reward', function (Blueprint $table) {
            $table->dropColumn(['no_surat']);
            $table->dropColumn(['to']);
        });
    }
}
