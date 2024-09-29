<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransRewardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trans_punishment', function (Blueprint $table) {
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
        });
        Schema::create('trans_reward', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tad_id')->nullable();
            $table->bigInteger('jenis_id')->nullable();
            $table->string('sk')->nullable();
            $table->date('tanggal_reward')->nullable();
            $table->text('keterangan')->nullable();

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('tad_id')->references('id')->on('ref_tad');
            // $table->foreign('jenis_id')->references('sandi')->on('Jenis_Reward');
        });

        Schema::create(
            'trans_reward_cc',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('reward_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->dateTime('read_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('sys_users');
                $table->foreign('reward_id')
                    ->references('id')
                    ->on('trans_reward')
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
        Schema::dropIfExists('trans_reward_reward');
        Schema::dropIfExists('trans_reward');
        Schema::table('trans_punishment', function (Blueprint $table) {
            $table->dropColumn(['updated_by', 'created_by']);
        });
    }
}
