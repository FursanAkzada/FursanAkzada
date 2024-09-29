<?php

namespace Modules\Settings\Database\Seeders;

use App\Entities\Group;
use App\Entities\Role;
use Illuminate\Database\Seeder;
use Modules\Settings\Entities\Menu;

class MenuFlowSeeder extends Seeder
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
                'code' => 'pengajuan',
                'name' => 'Pengajuan',
                'module' => 'pengajuan',
                'submenu' => [
                    [
                        'code'   => 'pengajuan.tad.form',
                        'name'   => 'TAD',
                        'module' => 'pengajuan.tad.form',
                        'submenu' => [
                            [
                                'code'   => 'tad-bod',
                                'name'   => 'TAD Direksi',
                                'module' => 'pengajuan.tad.form',
                                'flows'     => [
                                    [
                                        'group_id'  => Group::where('name', 'Pemimpin Cabang/Divisi')->first()->id,
                                        'type'      => 1,
                                        'order'     => 1,
                                    ],
                                    [
                                        'group_id'  => Group::where('name', 'Admin HC Rekrutmen')->first()->id,
                                        'type'      => 1,
                                        'order'     => 2,
                                    ],
                                    [
                                        'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                                        'type'      => 1,
                                        'order'     => 3,
                                    ],
                                ],
                            ],
                            [
                                'code'   => 'tad-vice',
                                'name'   => 'TAD SEVP',
                                'module' => 'pengajuan.tad.form',
                                'flows'     => [
                                    [
                                        'group_id'  => Group::where('name', 'Pemimpin Cabang/Divisi')->first()->id,
                                        'type'      => 1,
                                        'order'     => 1,
                                    ],
                                    [
                                        'group_id'  => Group::where('name', 'Admin HC Rekrutmen')->first()->id,
                                        'type'      => 1,
                                        'order'     => 2,
                                    ],
                                    [
                                        'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                                        'type'      => 1,
                                        'order'     => 3,
                                    ],
                                ],
                            ],
                            [
                                'code'   => 'tad-division',
                                'name'   => 'TAD Divisi',
                                'module' => 'pengajuan.tad.form',
                                'flows'     => [
                                    [
                                        'group_id'  => Group::where('name', 'Pemimpin Cabang/Divisi')->first()->id,
                                        'type'      => 1,
                                        'order'     => 1,
                                    ],
                                    [
                                        'group_id'  => Group::where('name', 'Admin HC Rekrutmen')->first()->id,
                                        'type'      => 1,
                                        'order'     => 2,
                                    ],
                                    [
                                        'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                                        'type'      => 1,
                                        'order'     => 3,
                                    ],
                                ],
                            ],
                            [
                                'code'   => 'tad-departemen',
                                'name'   => 'TAD Sub Divisi',
                                'module' => 'pengajuan.tad.form',
                                'flows'     => [
                                    [
                                        'group_id'  => Group::where('name', 'Pemimpin Cabang/Divisi')->first()->id,
                                        'type'      => 1,
                                        'order'     => 1,
                                    ],
                                    [
                                        'group_id'  => Group::where('name', 'Admin HC Rekrutmen')->first()->id,
                                        'type'      => 1,
                                        'order'     => 2,
                                    ],
                                    [
                                        'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                                        'type'      => 1,
                                        'order'     => 3,
                                    ],
                                ],
                            ],
                            [
                                'code'   => 'tad-cabang',
                                'name'   => 'TAD Cabang',
                                'module' => 'pengajuan.tad.form',
                                'flows'     => [
                                    [
                                        'group_id'  => Group::where('name', 'Pemimpin Cabang/Divisi')->first()->id,
                                        'type'      => 1,
                                        'order'     => 1,
                                    ],
                                    [
                                        'group_id'  => Group::where('name', 'Admin HC Rekrutmen')->first()->id,
                                        'type'      => 1,
                                        'order'     => 2,
                                    ],
                                    [
                                        'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                                        'type'      => 1,
                                        'order'     => 3,
                                    ],
                                ],
                            ],
                            [
                                'code'   => 'tad-capem',
                                'name'   => 'TAD Cabang Pembantu',
                                'module' => 'pengajuan.tad.form',
                                'flows'     => [
                                    [
                                        'group_id'  => Group::where('name', 'Pemimpin Cabang/Divisi')->first()->id,
                                        'type'      => 1,
                                        'order'     => 1,
                                    ],
                                    [
                                        'group_id'  => Group::where('name', 'Admin HC Rekrutmen')->first()->id,
                                        'type'      => 1,
                                        'order'     => 2,
                                    ],
                                    [
                                        'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                                        'type'      => 1,
                                        'order'     => 3,
                                    ],
                                ],
                            ],
                            [
                                'code'   => 'tad-kas',
                                'name'   => 'TAD Kantor Kas',
                                'module' => 'pengajuan.tad.form',
                                'flows'     => [
                                    [
                                        'group_id'  => Group::where('name', 'Pemimpin Cabang/Divisi')->first()->id,
                                        'type'      => 1,
                                        'order'     => 1,
                                    ],
                                    [
                                        'group_id'  => Group::where('name', 'Admin HC Rekrutmen')->first()->id,
                                        'type'      => 1,
                                        'order'     => 2,
                                    ],
                                    [
                                        'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                                        'type'      => 1,
                                        'order'     => 3,
                                    ],
                                ],
                            ],
                        ]
                    ],
                    [
                        'code'   => 'pengajuan.tad.kandidat',
                        'name'   => 'Kandidat',
                        'module' => 'pengajuan.tad.kandidat',
                        'flows'     => [
                            [
                                'group_id'  => Group::where('name', 'Admin HC Rekrutmen')->first()->id,
                                'type'      => 1,
                                'order'     => 1,
                            ],
                            [
                                'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                                'type'      => 1,
                                'order'     => 2,
                            ],
                        ],
                    ],
                    [
                        'code'   => 'pengajuan.tad.wawancara',
                        'name'   => 'Wawancara',
                        'module' => 'pengajuan.tad.wawancara',
                        'flows'     => [
                            [
                                'group_id'  => Group::where('name', 'Admin HC Rekrutmen')->first()->id,
                                'type'      => 1,
                                'order'     => 1,
                            ],
                            [
                                'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                                'type'      => 1,
                                'order'     => 2,
                            ],
                        ],
                    ],
                    [
                        'code'   => 'pengajuan.tad.penerimaan',
                        'name'   => 'Penerimaan',
                        'module' => 'pengajuan.tad.penerimaan',
                        'flows'     => [
                            [
                                'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                                'type'      => 1,
                                'order'     => 1,
                            ],
                            [
                                'group_id'  => Group::where('name', 'VP Human Capital')->first()->id,
                                'type'      => 1,
                                'order'     => 2,
                            ],
                        ],
                    ],
                ]
            ],
            [
                'code'   => 'resign_mutasi',
                'name'   => 'Resign & Mutasi',
                'module' => 'resign_mutasi',
                'submenu' => [
                    [
                        'code'   => 'resign_mutasi.resign',
                        'name'   => 'Pengajuan Resign',
                        'module' => 'resign_mutasi.resign',
                        'flows'     => [
                            [
                                'group_id'  => Group::where('name', 'Pemimpin Cabang/Divisi')->first()->id,
                                'type'      => 1,
                                'order'     => 1,
                            ],
                            [
                                'group_id'  => Group::where('name', 'Admin HC Rekrutmen')->first()->id,
                                'type'      => 1,
                                'order'     => 2,
                            ],
                            [
                                'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                                'type'      => 1,
                                'order'     => 3,
                            ],
                            [
                                'group_id'  => Group::where('name', 'VP Human Capital')->first()->id,
                                'type'      => 1,
                                'order'     => 4,
                            ],
                        ],
                    ],
                    [
                        'code'   => 'resign_mutasi.mutasi',
                        'name'   => 'Pengajuan Mutasi',
                        'module' => 'resign_mutasi.mutasi',
                        'flows'     => [
                            [
                                'group_id'  => Group::where('name', 'Pemimpin Cabang/Divisi')->first()->id,
                                'type'      => 1,
                                'order'     => 1,
                            ],
                            [
                                'group_id'  => Group::where('name', 'Admin HC Rekrutmen')->first()->id,
                                'type'      => 1,
                                'order'     => 2,
                            ],
                            [
                                'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                                'type'      => 1,
                                'order'     => 3,
                            ],
                            [
                                'group_id'  => Group::where('name', 'VP Human Capital')->first()->id,
                                'type'      => 1,
                                'order'     => 4,
                            ],
                        ],
                    ],
                ]
            ],
            [
                'code'   => 'personil.quota',
                'name'   => 'Quota TAD',
                'module' => 'personil.quota',
                'flows'     => [
                    [
                        'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                        'type'      => 1,
                        'order'     => 1,
                    ],
                ],
            ],
            [
                'code'   => 'penilaian',
                'name'   => 'Penilaian',
                'module' => 'penilaian',
                'submenu' => [
                    [
                        'code'   => 'penilaian.vendor',
                        'name'   => 'Vendor',
                        'module' => 'penilaian.vendor',
                        'flows'     => [
                            [
                                'group_id'  => Group::where('name', 'Pemimpin Cabang/Divisi')->first()->id,
                                'type'      => 1,
                                'order'     => 1,
                            ],
                            [
                                'group_id'  => Group::where('name', 'Admin HC Rekrutmen')->first()->id,
                                'type'      => 1,
                                'order'     => 2,
                            ],
                            [
                                'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                                'type'      => 1,
                                'order'     => 3,
                            ],
                        ],
                    ],
                    [
                        'code'   => 'penilaian.tad',
                        'name'   => 'TAD',
                        'module' => 'penilaian.tad',
                        'flows'     => [
                            [
                                'group_id'  => Group::where('name', 'Pemimpin Cabang/Divisi')->first()->id,
                                'type'      => 1,
                                'order'     => 1,
                            ],
                            [
                                'group_id'  => Group::where('name', 'Admin HC Rekrutmen')->first()->id,
                                'type'      => 1,
                                'order'     => 2,
                            ],
                            [
                                'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                                'type'      => 1,
                                'order'     => 3,
                            ],
                        ],
                    ],
                    [
                        'code'   => 'penilaian.perpanjangan',
                        'name'   => 'Perpanjangan Kontrak',
                        'module' => 'penilaian.perpanjangan',
                        'flows'     => [
                            [
                                'group_id'  => Group::where('name', 'Admin HC Rekrutmen')->first()->id,
                                'type'      => 1,
                                'order'     => 1,
                            ],
                        ],
                    ],
                ]
            ],
            [
                'code'   => 'pu',
                'name'   => 'Penghargaan & Pembinaan',
                'module' => 'pu',
                'submenu' => [
                    [
                        'code'   => 'pu.reward',
                        'name'   => 'Penghargaan',
                        'module' => 'pu.reward',
                        'flows'     => [
                            [
                                'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                                'type'      => 1,
                                'order'     => 1,
                            ],
                            [
                                'group_id'  => Group::where('name', 'VP Human Capital')->first()->id,
                                'type'      => 1,
                                'order'     => 2,
                            ],
                        ],
                    ],
                    [
                        'code'   => 'pu.pembinaan',
                        'name'   => 'Pembinaan',
                        'module' => 'pu.pembinaan',
                        'flows'     => [
                            [
                                'group_id'  => Group::where('name', 'AVP Operasional')->first()->id,
                                'type'      => 1,
                                'order'     => 1,
                            ],
                            [
                                'group_id'  => Group::where('name', 'VP Human Capital')->first()->id,
                                'type'      => 1,
                                'order'     => 2,
                            ],
                        ],
                    ],
                ]
            ],
        ];

        $this->generate($data);
    }

    public function generate($data)
    {
        ini_set("memory_limit", -1);

        $order = 1;
        $menu_ids = [];
        foreach ($data as $row) {
            $menu = Menu::firstOrNew(['module' => $row['module']]);
            $menu->code = $row['code'];
            $menu->module = $row['module'];
            $menu->name = $row['name'];
            $menu->order = $order;
            $menu->save();
            if (isset($row['flows'])) {
                $menu->groups()->sync($row['flows']);
            }
            $menu_ids[] = $menu->id;
            $order++;
            if (!empty($row['submenu'])) {
                foreach ($row['submenu'] as $val) {
                    $submenu = $menu->child()->firstOrNew(['module' => $val['module']]);
                    $submenu->code = $val['code'];
                    $submenu->module = $val['module'];
                    $submenu->name = $val['name'];
                    $submenu->order = $order;
                    $submenu->save();
                    if (isset($val['flows'])) {
                        $submenu->groups()->sync($val['flows']);
                    }
                    $menu_ids[] = $submenu->id;
                    $order++;

                    if (!empty($val['submenu'])) {
                        foreach ($val['submenu'] as $rec) {
                            $subsubmenu = $submenu->child()->firstOrNew(['code' => $rec['code']]);
                            $subsubmenu->module = $rec['module'];
                            $subsubmenu->name = $rec['name'];
                            $subsubmenu->order = $order;
                            $subsubmenu->save();
                            if (isset($rec['flows'])) {
                                $subsubmenu->groups()->sync($rec['flows']);
                            }
                            $menu_ids[] = $subsubmenu->id;
                            $order++;
                        }
                    }
                }
            }
        }
        Menu::whereNotIn('id', $menu_ids)->delete();
    }

    public function countActions($data)
    {
        $count = 0;
        foreach ($data as $row) {
            $count++;
            if (!empty($row['submenu'])) {
                $count += count($row['submenu']);
            }
        }
        return $count;
    }
}
