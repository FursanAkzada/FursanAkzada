<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransRewardAddStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_reward',
            function (Blueprint $table) {
                $table->string('status', 32)->after('id')
                    ->default('draft')
                    ->nullable();
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
            'trans_reward',
            function (Blueprint $table) {
                $table->dropColumn('status');
            }
        );
    }
}
