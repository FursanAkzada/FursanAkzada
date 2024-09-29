<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Entities\Tad\Keluarga;

class KeluargaSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        // Santi Mariska
        $data = Keluarga::firstOrCreate([
            'telepon' => '885522',
        ]);
        $data->tad_id = 1;
        $data->tipe_id = 1;
        $data->nama = 'Ahmad amin';
        $data->jenis_kelamin = 'L';
        $data->alamat = 'Bumi Panyawangan';
        $data->tempat_lahir = 'KOTA SURABAYA';
        $data->tanggal_lahir = '11/04/2023';
        $data->agama_id = 1;
        $data->kewarganegaraan = 'WNI';
        $data->urutan_anak = 1;
        $data->created_by = 5;
        $data->created_at = now();
        $data->save();

        $data = Keluarga::firstOrCreate([
            'telepon' => '112233',
        ]);
        $data->tad_id = 1;
        $data->tipe_id = 14;
        $data->nama = 'Indah';
        $data->jenis_kelamin = 'P';
        $data->alamat = 'Bumi Panyawangan';
        $data->tempat_lahir = 'KOTA SURABAYA';
        $data->tanggal_lahir = '11/04/2023';
        $data->agama_id = 1;
        $data->kewarganegaraan = 'WNI';
        $data->urutan_anak = 1;
        $data->created_by = 5;
        $data->created_at = now();
        $data->save();


        // Ferry Irawan
        $data = Keluarga::firstOrCreate([
            'telepon' => '212133',
        ]);
        $data->tad_id = 3;
        $data->tipe_id = 1;
        $data->nama = 'Wahid';
        $data->jenis_kelamin = 'L';
        $data->alamat = 'Bumi Panyawangan';
        $data->tempat_lahir = 'KAB. BANDUNG';
        $data->tanggal_lahir = '11/04/2023';
        $data->agama_id = 1;
        $data->kewarganegaraan = 'WNI';
        $data->urutan_anak = 1;
        $data->created_by = 5;
        $data->created_at = now();
        $data->save();

        $data = Keluarga::firstOrCreate([
            'telepon' => '669955',
        ]);
        $data->tad_id = 3;
        $data->tipe_id = 4;
        $data->nama = 'Farida';
        $data->jenis_kelamin = 'P';
        $data->alamat = 'Bumi Panyawangan';
        $data->tempat_lahir = 'KAB. BANDUNG';
        $data->tanggal_lahir = '11/04/2023';
        $data->agama_id = 1;
        $data->kewarganegaraan = 'WNI';
        $data->urutan_anak = 1;
        $data->created_by = 5;
        $data->created_at = now();
        $data->save();


        // Audi Marsya
        $data = Keluarga::firstOrCreate([
            'telepon' => '112113',
        ]);
        $data->tad_id = 2;
        $data->tipe_id = 10;
        $data->nama = 'Ferdy';
        $data->jenis_kelamin = 'L';
        $data->alamat = 'Bumi Panyawangan';
        $data->tempat_lahir = 'KAB. BANDUNG';
        $data->tanggal_lahir = '11/04/2023';
        $data->agama_id = 1;
        $data->kewarganegaraan = 'WNI';
        $data->urutan_anak = 1;
        $data->created_by = 5;
        $data->created_at = now();
        $data->save();

        $data = Keluarga::firstOrCreate([
            'telepon' => '333222',
        ]);
        $data->tad_id = 2;
        $data->tipe_id = 4;
        $data->nama = 'Sinta';
        $data->jenis_kelamin = 'P';
        $data->alamat = 'Bumi Panyawangan';
        $data->tempat_lahir = 'KAB. MALANG';
        $data->tanggal_lahir = '11/04/2023';
        $data->agama_id = 1;
        $data->kewarganegaraan = 'WNI';
        $data->urutan_anak = 1;
        $data->created_by = 5;
        $data->created_at = now();
        $data->save();


        // Venna Melinda
        $data = Keluarga::firstOrCreate([
            'telepon' => '444333',
        ]);
        $data->tad_id = 4;
        $data->tipe_id = 1;
        $data->nama = 'Tatang';
        $data->jenis_kelamin = 'L';
        $data->alamat = 'Bumi Panyawangan';
        $data->tempat_lahir = 'KAB. BANDUNG';
        $data->tanggal_lahir = '11/04/2023';
        $data->agama_id = 1;
        $data->kewarganegaraan = 'WNI';
        $data->urutan_anak = 1;
        $data->created_by = 5;
        $data->created_at = now();
        $data->save();

        $data = Keluarga::firstOrCreate([
            'telepon' => '444555',
        ]);
        $data->tad_id = 4;
        $data->tipe_id = 4;
        $data->nama = 'Fatimah';
        $data->jenis_kelamin = 'L';
        $data->alamat = 'Bumi Panyawangan';
        $data->tempat_lahir = 'KAB. MALANG';
        $data->tanggal_lahir = '11/04/2023';
        $data->agama_id = 1;
        $data->kewarganegaraan = 'WNI';
        $data->urutan_anak = 1;
        $data->created_by = 5;
        $data->created_at = now();
        $data->save();


        // Raffi Ahmad
        $data = Keluarga::firstOrCreate([
            'telepon' => '336699',
        ]);
        $data->tad_id = 6;
        $data->tipe_id = 1;
        $data->nama = 'Aceng';
        $data->jenis_kelamin = 'L';
        $data->alamat = 'Bumi Panyawangan';
        $data->tempat_lahir = 'KAB. GARUT';
        $data->tanggal_lahir = '11/04/2023';
        $data->agama_id = 1;
        $data->kewarganegaraan = 'WNI';
        $data->urutan_anak = 1;
        $data->created_by = 5;
        $data->created_at = now();
        $data->save();

        $data = Keluarga::firstOrCreate([
            'telepon' => '665544',
        ]);
        $data->tad_id = 6;
        $data->tipe_id = 4;
        $data->nama = 'Elly';
        $data->jenis_kelamin = 'P';
        $data->alamat = 'Bumi Panyawangan';
        $data->tempat_lahir = 'KAB. GARUT';
        $data->tanggal_lahir = '11/04/2023';
        $data->agama_id = 1;
        $data->kewarganegaraan = 'WNI';
        $data->urutan_anak = 2;
        $data->created_by = 5;
        $data->created_at = now();
        $data->save();
    }
}
