<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableRefTadAddDateOldContractNullable extends Migration
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
            'ref_tad',
            function (Blueprint $table) {
                $table->date('date_old_contract')->nullable()->after('rekomendasi');
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
            'ref_tad',
            function (Blueprint $table) {
                $table->dropColumn('date_old_contract');
            }
        );
    }
}
