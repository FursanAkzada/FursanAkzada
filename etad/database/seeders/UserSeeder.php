<?php

namespace Database\Seeders;

use App\Entities\EHC\User as EHCUser;
use App\Entities\Group;
use App\Entities\User;
use Illuminate\Database\Seeder;
use Modules\Master\Entities\SO\Positions;
use Modules\Master\Entities\SO\Unit;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group          = Group::firstOrCreate(['name' => 'Administrator']);
        $role           = Role::firstOrCreate(['name' => 'Administrator']);
        $group__pimpinan_unit_kerja     = Group::firstOrCreate(['name' => 'Pimpinan Unit Kerja']);
        $role__pimpinan_unit_kerja      = Role::firstOrCreate(['name' => 'Pimpinan Unit Kerja']);
        $group__officer_human_capital   = Group::firstOrCreate(['name' => 'Officer Human Capital']);
        $role__officer_human_capital    = Role::firstOrCreate(['name' => 'Officer Human Capital']);
        $group__psd_human_capital       = Group::firstOrCreate(['name' => 'PSD Human Capital']);
        $role__psd_human_capital        = Role::firstOrCreate(['name' => 'PSD Human Capital']);
        $group_vendor                   = Group::firstOrCreate(['name' => 'Vendor']);
        $role_vendor                    = Role::firstOrCreate(['name' => 'Vendor']);

        $password = bcrypt('password');

        $user_admin = User::where('name', 'User Developer')
            ->where('email', 'admin@email.com')
            ->where('username', 'admin')->first();
        if (!$user_admin) {
            $user_admin = new User;
            $user_admin->name = 'User Developer';
            $user_admin->email = 'admin@email.com';
            $user_admin->username = 'admin';
            $user_admin->user_type = 'ehc';
            $user_admin->kd_log = 'U000011';
            $user_admin->password = $password;
        }
        $user_admin->save();
        if (!$group->users()->where('id', $user_admin->id)->first()) {
            $user_admin = $group->users()->save($user_admin);
        }
        if (!$role->users()->where('id', $user_admin->id)->first()) {
            $user_admin = $role->users()->save($user_admin);
        }

        $USERS = [
            [
                'position_name' => 'Junior Officer HCIS dan Dapeg',
                'name'          => 'Dwiajeng Puspita R',
                'username'      => 'dwiajeng',
                'email'         => 'dwiajeng.ratri@bankjatim.co.id',
                'nik'           => '0000000000000001',
                'role_names'    => ['Officer Human Capital'],
            ],
            [
                'position_name' => 'Junior Officer Rekrutmen',
                'name'          => 'Ardina Sovitasari',
                'username'      => 'ardina',
                'email'         => 'ardinasovitasari@gmail.com',
                'nik'           => '0000000000000002',
                'role_names'    => ['Officer Human Capital'],
            ],
            [
                'position_name' => 'Officer Corporate Culture',
                'name'          => 'Daniel Dwi Putra S',
                'username'      => 'daniel',
                'email'         => 'danielbjtm2014@gmail.com',
                'nik'           => '0000000000000003',
                'role_names'    => ['Officer Human Capital'],
            ],
            [
                'position_name' => 'Funding Officer',
                'name'          => 'Asteryna Anandita',
                'username'      => 'asteryna',
                'email'         => 'asteryna.anandita@bankjatim.co.id',
                'nik'           => '0000000000000004',
                'role_names'    => ['Pimpinan Unit Kerja'],
            ],
            [
                'position_name' => 'Junior Officer IT Data Center',
                'name'          => 'Abdul Ghofar',
                'username'      => 'ghofar',
                'email'         => 'ghofar@bankjatim.co.id',
                'nik'           => '0000000000000005',
                'role_names'    => ['Pimpinan Unit Kerja'],
            ],
            [
                'position_name' => 'Junior Officer HCIS dan Dapeg',
                'name'          => 'Dovie Yudhawiratama',
                'username'      => 'dovie',
                'email'         => 'dovie.yudhawiratama@bankjatim.co.id',
                'nik'           => '0000000000000006',
                'role_names'    => ['Officer Human Capital'],
            ],
            [
                'position_name' => 'Pimpinan Divisi',
                'name'          => 'PSD Human Capital',
                'username'      => 'psd.hc',
                'email'         => 'psd.hc@email.com',
                'nik'           => '9999999999999999',
                'role_names'    => ['PSD Human Capital'],
            ],
            [
                'position_name' => 'AVP Operasional',
                'name'          => 'Arief',
                'username'      => 'arief',
                'email'         => 'arief@gmail.com',
                'nik'           => '9999999999999999',
                'role_names'    => ['AVP Operasional'],
            ],
            [
                'position_name' => 'Kepala Cabang Utama',
                'name'          => 'Staf HC Cabang Utama',
                'username'      => 'cabut',
                'email'         => 'cabut@gmail.com',
                'nik'           => '9999999999999999',
                'role_names'    => ['Staf HC'],
            ],
            [
                'position_name' => 'Kepala Cabang Utama',
                'name'          => 'Kepala Cabang Utama',
                'username'      => 'cab.utama',
                'email'         => 'cab.utama@gmail.com',
                'nik'           => '9999999999999999',
                'role_names'    => ['Pemimpin Cabang/Divisi'], //
            ],
            [
                'position_name' => 'AVP Operasional',
                'name'          => 'Fenty Rischana K',
                'username'      => 'fenty',
                'email'         => 'fenty@gmail.com',
                'nik'           => '9999999999999999',
                'role_names'    => ['AVP Operasional'],
            ],
            [
                'position_name' => 'VP Human Capital',
                'name'          => 'Slamet Purwanto',
                'username'      => 'slamet',
                'email'         => 'slamet@gmail.com',
                'nik'           => '9999999999999999',
                'role_names'    => ['VP Human Capital'],
            ],
            [
                'position_name' => 'Staf HC',
                'name'          => 'Staf HC Cabang Trenggalek',
                'username'      => 'trenggalek',
                'email'         => 'trenggalek@gmail.com',
                'nik'           => '9999999999999999',
                'role_names'    => ['Staf HC'],
            ],
            [
                'position_name' => 'Junior Officer Rekrutmen',
                'name'          => 'Muhammad Hudi',
                'username'      => 'hudi',
                'email'         => 'hudi@email.com',
                'nik'           => '9999999999999999',
                'role_names'    => ['Admin HC Rekrutmen'],
            ],
            [
                'position_name' => 'Kepala Cabang Madiun',
                'name'          => 'Cabang Madiun',
                'username'      => 'cab.madiun',
                'email'         => 'cab.madiun@email.com',
                'nik'           => '9999999999999999',
                'role_names'    => ['Pemimpin Cabang/Divisi'],
            ],
            [
                'position_name' => 'Kepala Cabang Trenggalek',
                'name'          => 'Kepala Cabang Trenggalek',
                'username'      => 'cab.trenggalek',
                'email'         => 'cab.trenggalek@email.com',
                'nik'           => '9999999999999999',
                'role_names'    => ['Pemimpin Cabang/Divisi'],
            ],
            //
            [
                'position_name' => NULL,
                'name'          => 'User Kopaba',
                'username'      => 'user.kopaba',
                'email'         => 'user.kopaba@email.com',
                'nik'           => '9999999999999999',
                'role_names'    => ['Vendor'],
                'is_vendor'     => true,
                'vendor_id'     => 1,
            ],
            [
                'position_name' => NULL,
                'name'          => 'User Persona',
                'username'      => 'user.persona',
                'email'         => 'user.persona@email.com',
                'nik'           => '9999999999999999',
                'role_names'    => ['Vendor'],
                'is_vendor'     => true,
                'vendor_id'     => 2,
            ],
            [
                'position_name' => NULL,
                'name'          => 'User Duta',
                'username'      => 'user.duta',
                'email'         => 'user.duta@email.com',
                'nik'           => '9999999999999999',
                'role_names'    => ['Vendor'],
                'is_vendor'     => true,
                'vendor_id'     => 3,
            ],
            [
                'position_name' => NULL,
                'name'          => 'User Bijak',
                'username'      => 'user.bijak',
                'email'         => 'user.bijak@email.com',
                'nik'           => '9999999999999999',
                'role_names'    => ['Vendor'],
                'is_vendor'     => true,
                'vendor_id'     => 4,
            ],
        ];

        try {
            foreach ($USERS as $key => $value) {
                $record = User::firstOrNew(['username' => $value['username']]);
                if (!$record->id) {
                    $record->password       = $password;
                }
                if (isset($value['position_name']) && $value['position_name']) {
                    $position = Positions::where('name', $value['position_name'])->first();
                    $record->position_id    = $position->id ?? null;
                }
                $record->email          = $value['email'];
                $record->nik            = $value['nik'];
                $record->username       = $value['username'];
                $record->name           = $value['name'];
                $record->user_type      = isset($value['is_vendor']) ? 'web' : 'ehc';
                $record->vendor_id      = $value['vendor_id'] ?? null;
                $record->save();
                if (isset($value['role_names'])) {
                    $record->roles()->sync(
                        [
                            Role::where('name', $value['role_names'])->first()->id
                        ]
                    );
                    $record->groups()->sync(
                        [
                            Group::where('name', $value['role_names'])->first()->id
                        ]
                    );
                }
                // dd(122, json_decode($record));
            }
        } catch (\Throwable $th) {
            dd($value);
            throw $th;
        }

        // USER VENDOR
        // $irna = User::where('name', 'Irna')
        //     ->where('email', 'irna@email.com')
        //     ->where('username', 'irna')->first();
        // if (!$irna) {
        //     $irna = new User;
        //     $irna->name = 'Irna';
        //     $irna->email = 'irna@email.com';
        //     $irna->username = 'irna';
        //     $irna->user_type = 'web';
        //     $irna->vendor_id = 6;
        //     $irna->password = $password;
        // }
        // $irna->save();
        // if (!$group_vendor->users()->where('id', $irna->id)->first()) {
        //     $irna = $group_vendor->users()->save($irna);
        // }
        // if (!$role_vendor->users()->where('id', $irna->id)->first()) {
        //     $irna = $role_vendor->users()->save($irna);
        // }

        // $dadang = User::where('name', 'Dadang')
        //     ->where('email', 'dadang@email.com')
        //     ->where('username', 'dadang')->first();
        // if (!$dadang) {
        //     $dadang = new User;
        //     $dadang->name = 'Dadang';
        //     $dadang->email = 'dadang@email.com';
        //     $dadang->username = 'dadang';
        //     $dadang->user_type = 'web';
        //     $dadang->vendor_id = 5;
        //     $dadang->password = $password;
        // }
        // $dadang->save();
        // if (!$group_vendor->users()->where('id', $dadang->id)->first()) {
        //     $dadang = $group_vendor->users()->save($dadang);
        // }
        // if (!$role_vendor->users()->where('id', $dadang->id)->first()) {
        //     $dadang = $role_vendor->users()->save($dadang);
        // }

        // $jujun = User::where('name', 'Jujun')
        //     ->where('email', 'jujun@email.com')
        //     ->where('username', 'jujun')->first();
        // if (!$jujun) {
        //     $jujun = new User;
        //     $jujun->name = 'Jujun';
        //     $jujun->email = 'jujun@email.com';
        //     $jujun->username = 'jujun';
        //     $jujun->user_type = 'web';
        //     $jujun->vendor_id = 3;
        //     $jujun->password = $password;
        // }
        // $jujun->save();
        // if (!$group_vendor->users()->where('id', $jujun->id)->first()) {
        //     $jujun = $group_vendor->users()->save($jujun);
        // }
        // if (!$role_vendor->users()->where('id', $jujun->id)->first()) {
        //     $jujun = $role_vendor->users()->save($jujun);
        // }
    }
}
