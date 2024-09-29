<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Entities\Resign\Reason;

class AlasanResignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'alasan'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non nisi erat',
            ],
            [
                'alasan'    => 'pharetra nisl posuere. Duis feugiat nisi lacus, ut imperdiet felis fringilla at',
            ],
            [
                'alasan'    => 'Vestibulum efficitur porta arcu, nec laoreet dui bibendum sed. Nunc laoreet eget elit fermentum sollicitudin.',
            ],
            [
                'alasan'    => 'Class aptent taciti sociosqu ad litora torquent per conubia nostra',
            ],
        ];

        foreach ($data as $val) {
            $record = Reason::firstOrNew(['alasan' => $val['alasan']]);
            $record->save();
        }
    }
}
