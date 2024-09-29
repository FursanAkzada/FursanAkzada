<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Modules\Master\Entities\SO\OrgStruct;

class CapemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path('database/seeders/json/_capem.json');
        $json = File::get($path);
        $data = json_decode($json);

        foreach ($data as $val) {
            $code = $val->code;
            $name = $val->name;
            $lower_name = strtolower($name);

            $parent_code = $val->parent_code ?? '-';
            $parent_name = $val->parent_name ?? '';
            $lower_parent_name = strtolower($parent_name);

            $struct = OrgStruct::where('code', $code)->first();
            if (!$struct) {
                $struct = new OrgStruct;
                $struct->code   = $code;
                $struct->level  = 'capem';
                $struct->name   = $name;
            }

            $parent_struct = OrgStruct::where('name', $parent_name)->first();
            if (!$parent_struct) {
                $parent_struct = new OrgStruct;
                $parent_struct->name = $parent_name;
                if (str_starts_with($lower_parent_name, 'direktur')) {
                    $parent_struct->level   = 'bod';
                } elseif (str_starts_with($lower_parent_name, 'sevp')) {
                    $parent_struct->level   = 'vice';
                } elseif (str_starts_with($lower_parent_name, 'divisi')) {
                    $parent_struct->level   = 'division';
                } elseif (str_starts_with($lower_parent_name, 'cab')) {
                    $parent_struct->level   = 'cabang';
                } else {
                    $parent_struct->level   = 'departemen';
                }
                $parent_struct->code = $parent_code;
            }
            $parent_struct->parent_id ??= 2;
            $parent_struct->city_id = 264;
            $parent_struct->province_id = 15;
            $parent_struct->save();
            if ($parent_struct->level) {
                $struct->parent_id  = $parent_struct->id ?? 2;
            } else {
                $struct->parent_id  = 2;
            }
            $struct->city_id = 264;
            $struct->province_id = 15;
            $struct->save();
            // if ($struct->name === 'Divisi Pengembangan Produk ....') {
            //     dd(json_decode($parent_struct));
            // }
        }
    }
}
