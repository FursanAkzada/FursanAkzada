<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Entities\Tad\Tad;

class AddFlag extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tad::whereHas(
            'kepegawaian',
            function ($q) {
                $q->whereNull('out_at');
            }
        )
            ->update(['available_status' => 2]);
    }
}
