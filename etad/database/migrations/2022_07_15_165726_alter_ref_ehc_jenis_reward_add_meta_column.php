<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRefEhcJenisRewardAddMetaColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'ref_ehc_jenis_reward',
            function (Blueprint $table) {
                $table->timestamps();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
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
            'ref_ehc_jenis_reward',
            function (Blueprint $table) {
                $table->dropColumn(
                    [
                        'created_by',
                        'updated_by',
                        'created_at',
                        'updated_at',
                    ]
                );
            }
        );
    }
}
