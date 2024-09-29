<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Entities\Tad\Tad;

class PersonilTadSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        // Santi Mariska
        $sm = Tad::firstOrCreate([
            'nik' => '1982309819028390',
            'npwp' => '918238123981092',
            'bpjs' => '9108230981293',
        ]);
        $sm->nama = 'Santi Mariska';
        $sm->email = 'santi@email.com';
        $sm->telepon = '198239082198390';
        $sm->jenis_kelamin = 'P';
        $sm->status_perkawinan = 1;
        $sm->agama_id = 1;
        $sm->tempat_lahir = 'KAB. PURWAKARTA';
        $sm->tanggal_lahir = '28/12/2022';
        $sm->alamat_lengkap = 'Surabaya';
        $sm->city_id = 232;
        $sm->pendidikan_id = 17;
        $sm->jabatan_id = 6;
        $sm->vendor_id = 7;
        $sm->is_active = 1;
        $sm->created_by = 5;
        $sm->created_at = now();
        $sm->save();

        // Audi Marsya
        $am = Tad::firstOrCreate([
            'nik' => '1982398129038912',
            'npwp' => '912831823120983',
            'bpjs' => '9182309812309',
        ]);
        $am->nama = 'Audi Marsya';
        $am->email = 'audi@email.com';
        $am->telepon = '123897182973891';
        $am->jenis_kelamin = 'P';
        $am->status_perkawinan = 1;
        $am->agama_id = 1;
        $am->tempat_lahir = 'KAB. PURWAKARTA';
        $am->tanggal_lahir = '28/12/2022';
        $am->alamat_lengkap = 'Surabaya';
        $am->city_id = 164;
        $am->pendidikan_id = 17;
        $am->jabatan_id = 6;
        $am->vendor_id = 7;
        $am->is_active = 1;
        $am->created_by = 5;
        $am->created_at = now();
        $am->save();

        // Ferry Irawan
        $fi = Tad::firstOrCreate([
            'nik' => '1311231312312313',
            'npwp' => '145456456464645',
            'bpjs' => '4565423123154',
        ]);
        $fi->nama = 'Ferry Irawan';
        $fi->email = 'ferry@email.com';
        $fi->telepon = '232145456432123';
        $fi->jenis_kelamin = 'L';
        $fi->status_perkawinan = 1;
        $fi->agama_id = 1;
        $fi->tempat_lahir = 'KOTA ADM. JAKARTA SELATAN';
        $fi->tanggal_lahir = '01/01/2023';
        $fi->alamat_lengkap = 'Jl. Jendral Gatot Subroto Kav. 24-25, Jakarta 12930, Indonesia';
        $fi->city_id = 158;
        $fi->pendidikan_id = 17;
        $fi->jabatan_id = 7;
        $fi->vendor_id = 7;
        $fi->is_active = 1;
        $fi->created_by = 5;
        $fi->updated_by = 5;
        $fi->created_at = now();
        $fi->save();

        // Venna Melinda
        $vm = Tad::firstOrCreate([
            'nik' => '5456432132146465',
            'npwp' => '564654312318978',
            'bpjs' => '2132123156489',
        ]);
        $vm->nama = 'Venna Melinda';
        $vm->email = 'venna@email.com';
        $vm->telepon = '123135646545645';
        $vm->jenis_kelamin = 'P';
        $vm->status_perkawinan = 1;
        $vm->agama_id = 1;
        $vm->tempat_lahir = 'KOTA ADM. JAKARTA PUSAT';
        $vm->tanggal_lahir = '27/12/2022';
        $vm->alamat_lengkap = 'Jl. Cibiru Hilir No.12a, Cibiru Hilir, Kec. Cileunyi, Kabupaten Bandung';
        $vm->city_id = 164;
        $vm->pendidikan_id = 17;
        $vm->jabatan_id = 9;
        $vm->vendor_id = 7;
        $vm->is_active = 1;
        $vm->created_by = 5;
        $vm->created_at = now();
        $vm->save();

        // Tukul Arwana
        $ta = Tad::firstOrCreate([
            'nik' => '8797645432132132',
            'npwp' => '878767465432132',
            'bpjs' => '7897897531321',
        ]);
        $ta->nama = 'Tukul Arwana';
        $ta->email = 'tukul@email.com';
        $ta->telepon = '545648765431321';
        $ta->jenis_kelamin = 'L';
        $ta->status_perkawinan = 1;
        $ta->agama_id = 1;
        $ta->tempat_lahir = 'KAB. BANDUNG';
        $ta->tanggal_lahir = '27/12/2022';
        $ta->alamat_lengkap = 'Jl. Cibiru Hilir No.12a, Cibiru Hilir, Kec. Cileunyi, Kabupaten Bandung';
        $ta->city_id = 164;
        $ta->pendidikan_id = 17;
        $ta->jabatan_id = 7;
        $ta->vendor_id = 7;
        $ta->is_active = 1;
        $ta->created_by = 5;
        $ta->created_at = now();
        $ta->save();

        // Raffi Ahmad
        $ra = Tad::firstOrCreate([
            'nik' => '9182390812903809',
            'npwp' => '192830918230981',
            'bpjs' => '1928301823908',
        ]);
        $ra->nama = 'Raffi Ahmad';
        $ra->email = 'raffi@email.com';
        $ra->telepon = '829018309801238';
        $ra->jenis_kelamin = 'L';
        $ra->status_perkawinan = 1;
        $ra->agama_id = 1;
        $ra->tempat_lahir = 'KAB. BANDUNG';
        $ra->tanggal_lahir = '01/02/2023';
        $ra->alamat_lengkap = 'Jl. Panyawungan, Cileunyi Wetan, Kec. Cileunyi, Kabupaten Bandung, Jawa Barat 40622';
        $ra->city_id = 164;
        $ra->pendidikan_id = 17;
        $ra->jabatan_id = 6;
        $ra->vendor_id = 7;
        $ra->is_active = 1;
        $ra->created_by = 5;
        $ra->created_at = now();
        $ra->save();

        // Enzy Storia
        $es = Tad::firstOrCreate([
            'nik' => '1923912039192091',
            'npwp' => '123788912731283',
            'bpjs' => '1982308123891',
        ]);
        $es->nama = 'Rusman Pragma';
        $es->email = 'rusman.pragma@gmail.com';
        $es->telepon = '089518792860';
        $es->jenis_kelamin = 'L';
        $es->status_perkawinan = 1;
        $es->agama_id = 1;
        $es->tempat_lahir = 'Kab Bandung';
        $es->tanggal_lahir = '01/02/2023';
        $es->alamat_lengkap = 'Jl. Cibiru Hilir No.12a, Cibiru Hilir, Kec. Cileunyi, Kabupaten BandDiklat Keagamaan Bandung, Jl. Soekarno Hatta, Babakan Penghulu, Kec. Cinambo, Kota Bandung, Jawa Barat';
        $es->city_id = 164;
        $es->pendidikan_id = 17;
        $es->jabatan_id = 9;
        $es->vendor_id = 7;
        $es->is_active = 1;
        $es->created_by = 5;
        $es->created_at = now();
        $es->save();

        // Deny Cagur
        $ta = Tad::firstOrCreate([
            'nik' => '1928309812903890',
            'npwp' => '182309182398120',
            'bpjs' => '9901283908129',
        ]);
        $ta->nama = 'Deny Cagur';
        $ta->email = 'deny@email.com';
        $ta->telepon = '182738912309801';
        $ta->jenis_kelamin = 'L';
        $ta->status_perkawinan = 1;
        $ta->agama_id = 1;
        $ta->tempat_lahir = 'KAB. CIANJUR';
        $ta->tanggal_lahir = '04/02/2023';
        $ta->alamat_lengkap = 'Jl. A.H. Nasution Jl. Ujungberung No.31, Cisaranten Bina Harapan, Kec. Arcamanik, Kota Bandung, Jawa Barat';
        $ta->city_id = 177;
        $ta->pendidikan_id = 38;
        $ta->jabatan_id = 7;
        $ta->vendor_id = 7;
        $ta->is_active = 1;
        $ta->created_by = 5;
        $ta->created_at = now();
        $ta->save();

        // Cak Lontong
        $ta = Tad::firstOrCreate([
            'nik' => '1928309182098109',
            'npwp' => '918237128731209',
            'bpjs' => '1928390128390',
        ]);
        $ta->nama = 'Cak Lontong';
        $ta->email = 'caklontong@email.com';
        $ta->telepon = '123908109283908';
        $ta->jenis_kelamin = 'L';
        $ta->status_perkawinan = 2;
        $ta->agama_id = 1;
        $ta->tempat_lahir = 'KOTA SURABAYA';
        $ta->tanggal_lahir = '05/01/2023';
        $ta->alamat_lengkap = 'Jl. Abdul Hamid No.5A, Jatihandap, Kec. Mandalajati, Kota Bandung, Jawa Barat 40195';
        $ta->city_id = 232;
        $ta->pendidikan_id = 38;
        $ta->jabatan_id = 7;
        $ta->vendor_id = 6;
        $ta->is_active = 1;
        $ta->created_by = 5;
        $ta->created_at = now();
        $ta->save();


        $ab = Tad::firstOrCreate([
            'nik' => '4455643213213211',
            'npwp' => '646546546545646',
            'bpjs' => '5465464646545',
        ]);
        $ab->nama = 'Aburizal Bakrie';
        $ab->email = 'bakrie@email.com';
        $ab->telepon = '127712973981273';
        $ab->jenis_kelamin = 'L';
        $ab->status_perkawinan = 2;
        $ab->agama_id = 1;
        $ab->tempat_lahir = 'KOTA SURABAYA';
        $ab->tanggal_lahir = '28/12/2022';
        $ab->alamat_lengkap = 'Tunjungan Plaza';
        $ab->city_id = 253;
        $ab->pendidikan_id = 31;
        $ab->jabatan_id = 4;
        $ab->vendor_id = 2;
        $ab->is_active = 1;
        $ab->created_by = 5;
        $ab->created_at = now();
        $ab->save();

        $jeff = Tad::firstOrCreate([
            'nik' => '1928098129038128',
            'npwp' => '912837123712897',
            'bpjs' => '1902398120398',
        ]);
        $jeff->nama = 'Jeff Bezos';
        $jeff->email = 'jeff@email.com';
        $jeff->telepon = '127309812098309';
        $jeff->jenis_kelamin = 'L';
        $jeff->status_perkawinan = 1;
        $jeff->agama_id = 3;
        $jeff->tempat_lahir = 'KOTA BALIKPAPAN';
        $jeff->tanggal_lahir = '03/01/2023';
        $jeff->alamat_lengkap = 'Kota Baru';
        $jeff->city_id = 338;
        $jeff->pendidikan_id = 38;
        $jeff->jabatan_id = 3;
        $jeff->vendor_id = 2;
        $jeff->is_active = 1;
        $jeff->created_by = 5;
        $jeff->created_at = now();
        $jeff->save();

        $dimas = Tad::firstOrCreate([
            'nik' => '1928390182398912',
            'npwp' => '918230981902831',
            'bpjs' => '9108230981092',
        ]);
        $dimas->nama = 'Dimas Damaruri';
        $dimas->email = 'dimas@email.com';
        $dimas->telepon = '187231237891273';
        $dimas->jenis_kelamin = 'L';
        $dimas->status_perkawinan = 1;
        $dimas->agama_id = 1;
        $dimas->tempat_lahir = 'KOTA MOJOKERTO';
        $dimas->tanggal_lahir = '28/12/2022';
        $dimas->alamat_lengkap = 'mojokerto';
        $dimas->city_id = 253;
        $dimas->pendidikan_id = 38;
        $dimas->jabatan_id = 5;
        $dimas->vendor_id = 2;
        $dimas->is_active = 1;
        $dimas->created_by = 5;
        $dimas->created_at = now();
        $dimas->save();

        $djainul = Tad::firstOrCreate([
            'nik' => '1829038129389081',
            'npwp' => '190283098120398',
            'bpjs' => '9102831237128',
        ]);
        $djainul->nama = 'Djainul';
        $djainul->email = 'djainul@email.com';
        $djainul->telepon = '192890812903892';
        $djainul->jenis_kelamin = 'L';
        $djainul->status_perkawinan = 1;
        $djainul->agama_id = 1;
        $djainul->tempat_lahir = 'KOTA SURABAYA';
        $djainul->tanggal_lahir = '28/12/2022';
        $djainul->alamat_lengkap = 'surabaya';
        $djainul->city_id = 232;
        $djainul->pendidikan_id = 38;
        $djainul->jabatan_id = 3;
        $djainul->vendor_id = 2;
        $djainul->is_active = 1;
        $djainul->created_by = 5;
        $djainul->created_at = now();
        $djainul->save();
    }
}
