<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SysApproval extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'sys_approval',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('targetable');
                $table->unsignedBigInteger('group_id')->nullable();
                $table->unsignedBigInteger('position_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedInteger('order')->default(1);
                $table->unsignedInteger('type')->nullable();
                $table->text('keterangan')->nullable();
                $table->string('status')->nullable();
                $table->dateTime('approved_at')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();

                $table->timestamps();
                $table->foreign('group_id')->references('id')->on('sys_groups');
                $table->foreign('position_id')->references('id')->on('ref_positions');
                $table->foreign('user_id')->references('id')->on('sys_users');
                
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
        Schema::dropIfExists('sys_approval');
    }
}
