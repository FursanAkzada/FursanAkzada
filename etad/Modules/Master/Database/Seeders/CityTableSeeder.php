<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\Geografis\City;
use File;
use Modules\Master\Entities\Geografis\Province;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path('Modules/Master/Database/Seeders/json/city.json');
        $json = File::get($path);
        $data = json_decode($json);

        // $this->command->getOutput()->progressStart(count($data));
        $this->generate($data);
        // $this->command->getOutput()->progressFinish();
    }

    public function generate($data)
    {
        foreach ($data as $val) {
            $prov = Province::where('code', $val->province_id)->first();
            $kab = City::where('code', $val->id)
                ->first();
            if (!$kab) {
                $kab = new City;
            }
            $kab->province_id = $prov->id;
            $kab->name = $val->name;
            $kab->code = $val->id;
            $kab->save();
            // $this->command->getOutput()->progressAdvance();
        }
    }
}
