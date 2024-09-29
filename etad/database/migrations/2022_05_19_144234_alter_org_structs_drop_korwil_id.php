<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrgStructsDropKorwilId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('ref_org_structs', 'korwil_id')) {
            Schema::table(
                'ref_org_structs',
                function (Blueprint $table) {
                    $table->dropColumn('korwil_id');
                }
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('ref_org_structs', 'korwil_id')) {
            Schema::table(
                'ref_org_structs',
                function (Blueprint $table) {
                    $table->unsignedBigInteger('korwil_id')->nullable();
                }
            );
        }
    }
}
