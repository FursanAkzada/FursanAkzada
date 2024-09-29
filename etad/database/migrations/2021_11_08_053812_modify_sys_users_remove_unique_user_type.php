<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySysUsersRemoveUniqueUserType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sys_users', function (Blueprint $table) {
            $table->dropUnique('sys_users_user_type_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sys_users', function (Blueprint $table) {
            $table->string('user_type')->nullable()->unique(true)->change();
        });
    }
}
