<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanResignAddSurat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_pengajuan_resign',
            function (Blueprint $table) {
                $table->string('no', 64)->nullable();
                $table->text('menunjuk')->nullable();
                $table->text('menindaklanjuti')->nullable();
            }
        );
        Schema::create(
            'trans_pengajuan_resign_to',
            function (Blueprint $table) {
                $table->unsignedBigInteger('trans_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();

                $table->foreign('user_id')
                    ->references('id')
                    ->on('ref_positions');
                $table->foreign('trans_id')
                    ->references('id')
                    ->on('trans_pengajuan_resign')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('trans_pengajuan_resign_to');
        Schema::table(
            'trans_pengajuan_resign',
            function (Blueprint $table) {
                $table->dropColumn('no');
                $table->dropColumn('menunjuk');
                $table->dropColumn('menindaklanjuti');
            }
        );
    }
}
