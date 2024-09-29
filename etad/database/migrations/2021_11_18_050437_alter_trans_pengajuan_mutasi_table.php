<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransPengajuanMutasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trans_pengajuan_mutasi', function (Blueprint $table) {
            $table->string('sk')->nullable();
            $table->text('sk_filename')->nullable();
            $table->text('sk_filepath')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trans_pengajuan_mutasi', function (Blueprint $table) {
            $table->dropColumn('sk');
            $table->dropColumn('sk_filename');
            $table->dropColumn('sk_filepath');
        });
    }
}
