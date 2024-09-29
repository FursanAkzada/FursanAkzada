<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPunishmentLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'trans_punishment_logs',
            function (Blueprint $table) {
                $table->id();
                $table->string('status', 16);
                $table->unsignedBigInteger('punishment_id');
                $table->string('keterangan', 256);
                $table->string('is_active', 1)->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();

                $table->foreign('punishment_id', 'punishment_id')
                    ->references('id')
                    ->on('trans_punishment');
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
        Schema::dropIfExists('trans_punishment_logs');
    }
}
