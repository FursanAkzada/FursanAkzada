<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\Geografis\Province;
use File;

class ProvinceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path('Modules/Master/Database/Seeders/json/province.json');
        $json = File::get($path);
        $data = json_decode($json);

        // $this->command->getOutput()->progressStart(count($data));
        $this->generate($data);
        // $this->command->getOutput()->progressFinish();
    }

    public function generate($data)
    {
        foreach ($data as $val) {
            $prov = Province::where('name', $val->name)->first();
            if (!$prov) {
                $prov = new Province;
            }
            // $prov->id = $val->id;
            $prov->name = $val->name;
            $prov->code = $val->id;
            $prov->save();
            // $this->command->getOutput()->progressAdvance();
        }
    }
}
