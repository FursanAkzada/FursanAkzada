<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'sys_menu',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('module')->nullable();
                $table->string('code');
                $table->string('name');
                $table->integer('order')->default(1);
                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('parent_id')->references('id')->on('sys_menu');
            }
        );

        Schema::create(
            'sys_menu_flows',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('menu_id');
                $table->unsignedBigInteger('group_id');
                $table->smallInteger('type')->default(1)->comment('0:pararel, 1:sequence');
                $table->integer('order')->default(1);
                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('menu_id')->references('id')->on('sys_menu')->onDelete('cascade');
                $table->foreign('group_id')->references('id')->on('sys_groups')->onDelete('cascade');
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
        Schema::dropIfExists('sys_menu_flows');
        Schema::dropIfExists('sys_menu');
    }
}
