<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'sys_files',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('target_id')->nullable();
                $table->string('target_type')->nullable();
                $table->string('name')->nullable();
                $table->string('path');
                $table->string('type')->nullable();
                $table->integer('size')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('sys_files');
    }
}
