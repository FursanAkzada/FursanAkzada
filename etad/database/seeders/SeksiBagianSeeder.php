<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Modules\Master\Entities\SO\OrgStruct;

class SeksiBagianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path('database/seeders/json/_seksi_bagian.json');
        $json = File::get($path);
        $data = json_decode($json);

        foreach ($data as $val) {
            // dd(25, $val, $val->nama_gab);
            $code = $val->CodeSB;
            $name = $val->nama_gab ?? null;
            if (!$name) {
                continue;
            }
            $lower_name = strtolower($name);

            $parent_code = $val->Sandi;
            $parent_name = $val->Lengkap ?? null;
            if (!$parent_name) {
                continue;
            }
            $parent_lower_name = strtolower($parent_name);

            $parent_struct = OrgStruct::where('code', $parent_code)->first();
            if (!$parent_struct) {
                $parent_struct = new OrgStruct;
                $parent_struct->code = $parent_code;
                $parent_struct->name = $parent_name;
            }
            if (str_starts_with($parent_lower_name, 'sevp')) {
                $parent_struct->level      = 'vice';
            } elseif (str_starts_with($parent_lower_name, 'divisi')) {
                // continue;
                $parent_struct->level      = 'division';
            } elseif (str_starts_with($parent_lower_name, 'cab')) {
                $parent_struct->level      = 'cabang';
            } else {
                continue;
            }
            if (!$parent_struct->parent_id) {
                $parent_struct->parent_id = 2;
            }
            $parent_struct->city_id = 264;
            $parent_struct->province_id = 15;
            $parent_struct->save();

            // $bagian = OrgStruct::where('code', $code)->first();
            // if (!$bagian) {
            //     $bagian = new OrgStruct;
            //     $bagian->code       = $code;
            //     $bagian->name       = $name;
            //     $bagian->level      = 'bagian';
            //     $bagian->parent_id  = $parent_struct->id;
            //     $bagian->created_by ??= 1;
            //     $bagian->created_at ??= \Carbon\Carbon::now();
            //     $bagian->save();
            // }
        }
    }
}
