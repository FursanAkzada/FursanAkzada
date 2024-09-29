<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\Geografis\District;
use File;
use Modules\Master\Entities\Geografis\City;

class DistrictTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path('Modules/Master/Database/Seeders/json/district.json');
        $json = File::get($path);
        $data = json_decode($json);

        // $this->command->getOutput()->progressStart(count($data));
        $this->generate($data);
        // $this->command->getOutput()->progressFinish();
    }

    public function generate($data)
    {
        foreach ($data as $val) {
            $city = City::where('code', $val->city_id)->first();
            $kec = District::where('code', $val->id)
                ->first();
            if (!$kec) {
                $kec = new District;
            }
            $kec->code = $val->id;
            $kec->city_id = $city->id;
            $kec->name = $val->name;
            $kec->created_by = $val->created_by;
            $kec->created_at = \Carbon\Carbon::now();
            $kec->save();
            // $this->command->getOutput()->progressAdvance();
        }
    }
}
