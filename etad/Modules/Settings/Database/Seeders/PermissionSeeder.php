<?php

namespace Modules\Settings\Database\Seeders;

use App\Entities\Group;
use App\Entities\Permission;
use App\Entities\Role;
use Illuminate\Database\Seeder;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name'          => 'dashboard',
                'display_name'  => 'Dashboard',
                'action'        => ['view'],
            ],
            [
                'name'          => 'pengajuan.tad.form',
                'display_name'  => 'Pengajuan TAD',
                'action'        => ['view', 'add', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'pengajuan.tad.kandidat',
                'display_name'  => 'Kandidat',
                'action'        => ['view', 'add', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'pengajuan.tad.wawancara',
                'display_name'  => 'Wawancara',
                'action'        => ['view', 'add', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'pengajuan.tad.penerimaan',
                'display_name'  => 'Penerimaan TAD',
                'action'        => ['view', 'add', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'resign_mutasi.resign',
                'display_name'  => 'Pengajuan Resign',
                'action'        => ['view', 'add', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'resign_mutasi.mutasi',
                'display_name'  => 'Pengajuan Mutasi',
                'action'        => ['view', 'add', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'personil.migrasi',
                'display_name'  => 'Migrasi Personil',
                'action'        => ['view', 'add'],
            ],
            [
                'name'          => 'personil.aktif',
                'display_name'  => 'Personil Atif',
                'action'        => ['view', 'edit'],
            ],
            [
                'name'          => 'personil.nonaktif',
                'display_name'  => 'Personil Non Aktif',
                'action'        => ['view', 'edit', 'delete'],
            ],
            [
                'name'          => 'personil.unemployed',
                'display_name'  => 'Personil Unemployed',
                'action'        => ['view', 'add', 'edit', 'delete'],
            ],
            [
                'name'          => 'personil.failed',
                'display_name'  => 'Gagal Upload',
                'action'        => ['view', 'delete'],
            ],
            [
                'name'          => 'personil.quota',
                'display_name'  => 'Quota Pengajuan TAD',
                'action'        => ['view', 'add', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'penilaian.vendor',
                'display_name'  => 'Penilaian Vendor',
                'action'        => ['view', 'add', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'penilaian.tad',
                'display_name'  => 'Penilaian TAD',
                'action'        => ['view', 'add', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'penilaian.perpanjangan',
                'display_name'  => 'Perpanjangan Kontrak TAD',
                'action'        => ['view', 'add', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'pu.reward',
                'display_name'  => 'Reward',
                'action'        => ['view', 'add', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'pu.pembinaan',
                'display_name'  => 'Pembinaan',
                'action'        => ['view', 'add', 'edit', 'delete', 'approve'],
            ],
            [
                'name'          => 'pu.punishment',
                'display_name'  => 'Punishment',
                'action'        => ['view', 'add', 'edit', 'delete', 'approve'],
            ],

            [
                'name'          => 'master',
                'display_name'  => 'Master',
                'action'        => ['view', 'add', 'edit', 'delete'],
            ],
            [
                'name'          => 'setting',
                'display_name'  => 'Setting',
                'action'        => ['view', 'add', 'edit', 'delete'],
            ],
        ];

        $this->generate($permissions);

        $roles = [
            [
                'name' => 'Administrator',
                'permissions' => [
                    'dashboard' => ['view'],
                    'master' => ['view', 'add', 'edit', 'delete'],
                    'setting' => ['view', 'add', 'edit', 'delete'],
                ],
            ],
            [
                'name' => 'Vendor',
                'permissions' => [
                    'dashboard'                 => ['view'],
                    'pengajuan.tad.form'        => ['view', 'add', 'edit', 'delete'],
                    'pengajuan.tad.kandidat'    => ['view', 'add', 'edit', 'delete'],
                    'pengajuan.tad.wawancara'   => ['view', 'add', 'edit', 'delete'],
                    'pengajuan.tad.penerimaan'  => ['view', 'add', 'edit', 'delete'],
                    'resign_mutasi.resign'      => ['view', 'add', 'edit', 'delete'],
                    'resign_mutasi.mutasi'      => ['view', 'add', 'edit', 'delete'],
                    'personil.migrasi'          => ['view', 'add', 'edit', 'delete'],
                    'personil.aktif'            => ['view', 'add', 'edit', 'delete'],
                    'personil.nonaktif'         => ['view', 'add', 'edit', 'delete'],
                    'personil.unemployed'       => ['view', 'add', 'edit', 'delete'],
                    'personil.failed'           => ['view', 'add', 'edit', 'delete'],
                    'penilaian.vendor'          => ['view'],
                    'penilaian.tad'             => ['view'],
                    'penilaian.perpanjangan'    => ['view'],
                    'pu.reward'                 => ['view'],
                    'pu.pembinaan'              => ['view'],
                ],
            ],
            [
                'name' => 'Pimpinan Unit Kerja',
                'permissions' => [
                    'dashboard'                 => ['view'],
                    'pengajuan.tad.form'        => ['view', 'add', 'edit', 'delete'],
                    'pengajuan.tad.kandidat'    => ['view', 'add', 'edit', 'delete'],
                    'pengajuan.tad.wawancara'   => ['view', 'add', 'edit', 'delete'],
                    'pengajuan.tad.penerimaan'  => ['view', 'add', 'edit', 'delete'],
                    'resign_mutasi.resign'      => ['view', 'add', 'edit', 'delete'],
                    'resign_mutasi.mutasi'      => ['view', 'add', 'edit', 'delete'],
                    'personil.migrasi'          => ['view', 'add', 'edit', 'delete'],
                    'personil.aktif'            => ['view', 'add', 'edit', 'delete'],
                    'personil.nonaktif'         => ['view', 'add', 'edit', 'delete'],
                    'personil.unemployed'       => ['view', 'add', 'edit', 'delete'],
                    'personil.failed'           => ['view', 'add', 'edit', 'delete'],
                    'penilaian.vendor'          => ['view', 'add', 'edit', 'delete'],
                    'penilaian.tad'             => ['view', 'add', 'edit', 'delete'],
                    'penilaian.perpanjangan'    => ['view', 'add', 'edit', 'delete'],
                    'pu.reward'                 => ['view', 'add', 'edit', 'delete'],
                    'pu.pembinaan'              => ['view', 'add', 'edit', 'delete'],
                ],
            ],
            [
                'name' => 'PSD Human Capital',
                'permissions' => [
                    'dashboard' => ['view'],
                    'pengajuan.tad.form' => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.kandidat' => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.wawancara' => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.penerimaan' => ['view', 'add', 'edit', 'delete', 'approve'],
                    'resign_mutasi.resign' => ['view', 'add', 'edit', 'delete', 'approve'],
                    'resign_mutasi.mutasi' => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.quota' => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.migrasi' => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.aktif' => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.nonaktif' => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.unemployed' => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.failed' => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.vendor' => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.tad' => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.perpanjangan' => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pu.reward' => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pu.pembinaan' => ['view', 'add', 'edit', 'delete', 'approve'],

                    'master' => ['view', 'add', 'edit', 'delete'],
                    'setting' => ['view', 'add', 'edit', 'delete'],
                ],
            ],
            [
                'name' => 'Officer Human Capital',
                'permissions' => [
                    'dashboard' => ['view'],
                    'pengajuan.tad.form' => ['view', 'add', 'edit', 'delete'],
                    'pengajuan.tad.kandidat' => ['view', 'add', 'edit', 'delete'],
                    'pengajuan.tad.wawancara' => ['view', 'add', 'edit', 'delete'],
                    'pengajuan.tad.penerimaan' => ['view', 'add', 'edit', 'delete'],
                    'resign_mutasi.resign' => ['view', 'add', 'edit', 'delete'],
                    'resign_mutasi.mutasi' => ['view', 'add', 'edit', 'delete'],
                    'personil.quota' => ['view', 'add', 'edit', 'delete'],
                    'personil.migrasi' => ['view', 'add', 'edit', 'delete'],
                    'personil.aktif' => ['view', 'add', 'edit', 'delete'],
                    'personil.nonaktif' => ['view', 'add', 'edit', 'delete'],
                    'personil.unemployed' => ['view', 'add', 'edit', 'delete'],
                    'personil.failed' => ['view', 'add', 'edit', 'delete'],
                    'penilaian.vendor' => ['view', 'add', 'edit', 'delete'],
                    'penilaian.tad' => ['view', 'add', 'edit', 'delete'],
                    'penilaian.perpanjangan' => ['view', 'add', 'edit', 'delete'],
                    'pu.reward' => ['view', 'add', 'edit', 'delete'],
                    'pu.pembinaan' => ['view', 'add', 'edit', 'delete'],
                ],
            ],
            [
                'name' => 'Pemimpin Cabang/Divisi',
                'permissions' => [
                    'dashboard' => ['view'],
                    'pengajuan.tad.form'        => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.kandidat'    => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.wawancara'   => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.penerimaan'  => ['view', 'add', 'edit', 'delete', 'approve'],
                    'resign_mutasi.resign'      => ['view', 'add', 'edit', 'delete', 'approve'],
                    'resign_mutasi.mutasi'      => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.quota'            => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.migrasi'          => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.aktif'            => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.nonaktif'         => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.unemployed'       => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.failed'           => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.vendor'          => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.tad'             => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.perpanjangan'    => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pu.reward'                 => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pu.pembinaan'              => ['view', 'add', 'edit', 'delete', 'approve'],
                ],
            ],
            [
                'name' => 'AVP Operasional',
                'permissions' => [
                    'dashboard' => ['view'],
                    'pengajuan.tad.form'        => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.kandidat'    => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.wawancara'   => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.penerimaan'  => ['view', 'add', 'edit', 'delete', 'approve'],
                    'resign_mutasi.resign'      => ['view', 'add', 'edit', 'delete', 'approve'],
                    'resign_mutasi.mutasi'      => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.quota'            => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.migrasi'          => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.aktif'            => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.nonaktif'         => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.unemployed'       => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.failed'           => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.vendor'          => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.tad'             => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.perpanjangan'    => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pu.reward'                 => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pu.pembinaan'              => ['view', 'add', 'edit', 'delete', 'approve'],
                ],
            ],
            [
                'name' => 'Staf HC',
                'permissions' => [
                    'dashboard' => ['view'],
                    'pengajuan.tad.form'        => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.kandidat'    => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.wawancara'   => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.penerimaan'  => ['view', 'add', 'edit', 'delete', 'approve'],
                    'resign_mutasi.resign'      => ['view', 'add', 'edit', 'delete', 'approve'],
                    'resign_mutasi.mutasi'      => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.quota'            => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.migrasi'          => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.aktif'            => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.nonaktif'         => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.unemployed'       => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.failed'           => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.vendor'          => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.tad'             => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.perpanjangan'    => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pu.reward'                 => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pu.pembinaan'              => ['view', 'add', 'edit', 'delete', 'approve'],
                ],
            ],
            [
                'name' => 'VP Human Capital',
                'permissions' => [
                    'dashboard'                 => ['view'],
                    'pengajuan.tad.form'        => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.kandidat'    => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.wawancara'   => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.penerimaan'  => ['view', 'add', 'edit', 'delete', 'approve'],
                    'resign_mutasi.resign'      => ['view', 'add', 'edit', 'delete', 'approve'],
                    'resign_mutasi.mutasi'      => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.quota'            => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.migrasi'          => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.aktif'            => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.nonaktif'         => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.unemployed'       => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.failed'           => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.vendor'          => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.tad'             => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.perpanjangan'    => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pu.reward'                 => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pu.pembinaan'              => ['view', 'add', 'edit', 'delete', 'approve'],
                ],
            ],
            [
                'name' => 'Admin HC Rekrutmen',
                'permissions' => [
                    'dashboard'                 => ['view'],
                    'pengajuan.tad.form'        => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.kandidat'    => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.wawancara'   => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pengajuan.tad.penerimaan'  => ['view', 'add', 'edit', 'delete', 'approve'],
                    'resign_mutasi.resign'      => ['view', 'add', 'edit', 'delete', 'approve'],
                    'resign_mutasi.mutasi'      => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.quota'            => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.migrasi'          => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.aktif'            => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.nonaktif'         => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.unemployed'       => ['view', 'add', 'edit', 'delete', 'approve'],
                    'personil.failed'           => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.vendor'          => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.tad'             => ['view', 'add', 'edit', 'delete', 'approve'],
                    'penilaian.perpanjangan'    => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pu.reward'                 => ['view', 'add', 'edit', 'delete', 'approve'],
                    'pu.pembinaan'              => ['view', 'add', 'edit', 'delete', 'approve'],
                ],
            ],
        ];
        $_perms = [];
        $_perm_ids = [];
        foreach ($roles as $role) {
            $_role = Role::firstOrCreate(['name' => $role['name']]);
            $_group = Group::firstOrCreate(['name' => $role['name']]);
            $perms = [];
            // dd(224, $role);
            foreach ($role['permissions'] as $module => $actions) {
                foreach ($actions as $action) {
                    $perms[] = $module . '.' . $action;
                }
            }
            $perm_ids = Permission::whereIn('name', $perms)->pluck('id');
            // $_perms[] = $perms;
            // $_perm_ids[] = $perm_ids;
            $_role->syncpermissions($perm_ids);
            $_group->syncpermissions($perm_ids);
        }
        // dd(235, $_perms);
    }

    public function generate($permissions)
    {
        ini_set("memory_limit", -1);

        foreach ($permissions as $row) {
            foreach ($row['action'] as $key => $val) {
                $temp = [
                    'name'         => $row['name'] . '.' . $val,
                    'display_name' => $row['display_name'] . ' ' . ucfirst($val)
                ];
                $perms = Permission::where('name', $temp['name'])->first();
                if (!$perms) {
                    $perms = new Permission;
                }
                $perms->fill($temp);
                $perms->save();
                // if ($row['certain']) continue;
                // $roles = Role::all();
                // $perms->roles()->syncWithoutDetaching($roles->pluck('id'));
            }
        }
    }
}
