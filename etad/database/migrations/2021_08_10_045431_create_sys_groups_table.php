<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('sys_groups_roles', function (Blueprint $table) {
            $table->bigInteger('group_id')->nullable();
            $table->bigInteger('role_id')->nullable();
        });

        Schema::create('sys_groups_users', function (Blueprint $table) {
            $table->bigInteger('group_id')->nullable();
            $table->bigInteger('user_id')->nullable();
        });

        Schema::create('ref_unit',function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('code')->nullable();
                $table->string('mailing')->nullable();
                $table->string('description')->nullable();
                $table->smallInteger('is_active')->nullable()->default(1);

                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();
        });

        Schema::table('sys_roles', function (Blueprint $table) {;
            $table->bigInteger('parent_id')->nullable()->unsigned()->after('id');
            $table->bigInteger('unit_id')->nullable()->unsigned()->after('parent_id');
            $table->string('code')->nullable()->after('unit_id');
            $table->string('group')->nullable()->after('guard_name');
            $table->smallInteger('is_active')->default(1)->after('group');
            
            $table->foreign('unit_id')->references('id')->on('ref_unit');
            $table->foreign('parent_id')->references('id')->on('sys_roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_groups');
        Schema::dropIfExists('sys_groups_roles');
        Schema::dropIfExists('sys_groups_users');
        Schema::table('sys_roles', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
            $table->dropForeign(['unit_id']);
            
            $table->dropColumn('group');
            $table->dropColumn('is_active');
            $table->dropColumn('unit_id');
            if (Schema::hasColumn('sys_roles', 'code')) {
                $table->dropColumn('code');
            }
        });
        Schema::dropIfExists('ref_unit');
    }
}
