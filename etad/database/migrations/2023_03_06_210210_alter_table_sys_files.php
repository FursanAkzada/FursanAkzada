<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableSysFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sys_files', function (Blueprint $table) {
            $table->string('module')->nullable()->after('target_type');
            $table->string('file_name')->after('module');
            $table->string('file_path')->after('file_name');
            $table->string('file_size')->nullable()->after('file_path');
            $table->string('flag')->nullable()->after('file_size');
            $table->dropColumn(['name', 'path', 'type', 'size']);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
        });

        Schema::create('temp_files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('path');
            $table->string('size')->nullable();
            $table->string('flag')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_files');

        Schema::table('sys_files', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('path');
            $table->string('type')->nullable();
            $table->integer('size')->nullable();
            $table->dropColumn(['module', 'file_name', 'file_path', 'file_size', 'flag']);
        });
    }
}
