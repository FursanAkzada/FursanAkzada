<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\SO\Positions;

class OrgStructTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->saveRoot();
        $this->saveBod();
        $this->saveSevp();
        $this->saveDivisi();
        $this->saveCabang();
    }

    public function saveRoot()
    {
        // Root hanya boleh ada 1
        $address    = 'Jl. Basuki Rahmat No.98-104';

        $root = OrgStruct::firstOrNew(['level' => 'root', 'parent_id' => null]);
        $root->fill(
            [
                'name'          => "PT BANK PEMBANGUNAN DAERAH JAWA TIMUR Tbk",
                'province_id'   => 15,
                'city_id'       => 264,
                'phone'         => '(031) 531 0090 - 99',
                'fax'           => '(031) 531 0838',
                'address'       => $address,
                'status'        => 1,
            ]
        );
        $root->save();
    }

    public function saveBod()
    {
        $root = OrgStruct::firstOrNew(['level' => 'bod', 'parent_id' => 1]);
        $root->fill(
            [
                'name'          => 'Presiden Direktur',
                'status'        => 1,
            ]
        );
        $root->save();

        $bod = [
            [
                'name' => 'Presiden Direktur',
                'bod_childs'    => [
                    [
                        'name'      => 'Direktur Pemasaran',
                        'branches'  => [],
                        'vp'        => [
                            [
                                'name'  => 'VP Pengembangan Bisnis',
                            ],
                        ],
                    ],
                    [
                        'name'      => 'Direktur SDM',
                        'branches'  => [],
                        'vp'        => [
                            [
                                'name'  => 'VP Manajemen SDM',
                            ]
                        ],
                    ],
                    [
                        'name'      => 'Direktur TI & Operasi',
                        'branches'  => [
                            [
                                'code'  => 'C01',
                                'city'  => '3578',
                                'name'  => 'Cabang Utama',
                                'sub_branches'   => [
                                    [
                                        'code'  => 'C0101',
                                        'name'  => 'Capem Klampis Jaya',
                                    ],
                                    [
                                        'code'  => 'C0102',
                                        'name'  => 'Capem Juanda',
                                    ],
                                    [
                                        'code'  => 'C0103',
                                        'name'  => 'Capem UWK',
                                    ]
                                ],
                                // 'cash_offices' => [
                                //     [
                                //         'name'  => 'Kas Rungkut',
                                //     ],
                                //     [
                                //         'name'  => 'Kas Tunjungan Plaza',
                                //     ],
                                // ]
                            ],
                            [
                                'code'  => 'C02',
                                'city'  => '3510',
                                'name'  => 'Cabang Banyuwangi',
                                'sub_branches'  => [],
                            ],
                            [
                                'code'  => 'C03',
                                'city'  => '3509',
                                'name'  => 'Cabang Jember',
                                'sub_branches'  => [],
                            ],
                            [
                                'code'  => 'C04',
                                'city'  => '3573',
                                'name'  => 'Cabang Malang',
                                'sub_branches'  => [],
                            ],
                            [
                                'code'  => 'C05',
                                'city'  => '3577',
                                'name'  => 'Cabang Madiun',
                                'sub_branches'  => [],
                            ],
                            [
                                'code'  => 'C06',
                                'city'  => '264',
                                'name'  => 'Cabang Batu',
                                'sub_branches'  => [],
                            ],
                        ],
                        'vp'        => [],
                    ],
                    [
                        'name'      => 'Direktur Keuangan',
                        'branches'  => [],
                        'vp'        => [
                            [
                                'name'  => 'SEVP Perencanaan Strategis & Pembinaan Cabang'
                            ]
                        ]
                    ],
                    [
                        'name'      => 'Direktur Kepatuhan & Manajemen Resiko',
                        'branches'  => [],
                        'vp'        => [],
                    ],
                ]
            ]
        ];
        foreach ($bod as $item) {
            $bod_parent = OrgStruct::where('level', 'bod')->where('name', $item['name'])->first();
            if (is_null($bod_parent)) {
                $bod_parent = new OrgStruct;
            }
            $bod_parent->level     = 'bod';
            $bod_parent->type      = 0;
            if (!$bod_parent->parent_id) {
                $bod_parent->parent_id = 2;
            }
            $bod_parent->name      = $item['name'];
            $bod_parent->status    = 1;
            $bod_parent->city_id   = 264;
            $bod_parent->province_id = 15;
            $bod_parent->save();
            foreach ($item['bod_childs'] as $key => $child) {
                $bod_child = OrgStruct::where('level', 'bod')->where('name', $child['name'])->first();
                if (is_null($bod_child)) {
                    $bod_child = new OrgStruct;
                }
                $bod_child->level       = 'bod';
                $bod_child->type        = 0;
                $bod_child->parent_id   = $bod_parent->id;
                $bod_child->name        = $child['name'];
                $bod_child->status      = 1;
                $bod_child->city_id   = 264;
                $bod_child->province_id = 15;
                $bod_child->save();
                foreach ($child['branches'] as $key => $branch) {
                    $cabang = OrgStruct::where('level', 'cabang')->where('code', $branch['code'])->first();
                    if (is_null($cabang)) {
                        $cabang = new OrgStruct;
                    }
                    $cabang->level      = 'cabang';
                    $cabang->type       = 0;
                    $cabang->parent_id  = $bod_child->id;
                    $cabang->code       = $branch['code'];
                    $cabang->name       = $branch['name'];
                    $cabang->status     = 1;
                    $cabang->city_id   = 264;
                    $cabang->province_id = 15;
                    $cabang->save();
                    foreach ($branch['sub_branches'] as $key => $sub_branch) {
                        $capem = OrgStruct::where('level', 'capem')->where('code', $sub_branch['code'])->first();
                        if (is_null($capem)) {
                            $capem = new OrgStruct;
                        }
                        $capem->level      = 'capem';
                        $capem->type       = 0;
                        $capem->parent_id  = $cabang->id;
                        $capem->code       = $sub_branch['code'];
                        $capem->name       = $sub_branch['name'];
                        $capem->status     = 1;
                        $capem->city_id   = 264;
                        $capem->province_id = 15;
                        $capem->save();
                    }
                    foreach (($branch['cash_offices'] ?? []) as $key => $sub_branch) {
                        $kantor_kas = OrgStruct::where('level', 'kas')->where('name', $sub_branch['name'])->first();
                        if (is_null($kantor_kas)) {
                            $kantor_kas = new OrgStruct;
                        }
                        $kantor_kas->level      = 'kas';
                        $kantor_kas->type       = 0;
                        $kantor_kas->parent_id  = $cabang->id;
                        $kantor_kas->code       = $sub_branch['code'] ?? null;
                        $kantor_kas->name       = $sub_branch['name'];
                        $kantor_kas->status     = 1;
                        $kantor_kas->city_id   = 264;
                        $kantor_kas->province_id = 15;
                        $kantor_kas->save();
                    }
                }
                foreach ($child['vp'] as $key => $vp_) {
                    $vp = OrgStruct::where('level', 'vice')->where('name', $vp_['name'])->first();
                    if (is_null($vp)) {
                        $vp = new OrgStruct;
                    }
                    $vp->level      = 'vice';
                    $vp->type       = 0;
                    $vp->parent_id  = $bod_child->id;
                    $vp->name       = $vp_['name'];
                    $vp->status     = 1;
                    $vp->city_id   = 264;
                    $vp->province_id = 15;
                    $vp->save();
                }
            }
        }
    }

    public function saveSevp()
    {
        $vice  = [
            [
                'parent'    => 'Presiden Direktur',
                'name'      => 'SEVP Network and Services',
                'code'      => 'A63',
            ],
            [
                'parent'    => 'Direktur Konsumer, Ritel dan Usaha Syariah',
                'name'      => 'SEVP Unit Usaha Syariah',
                'code'      => 'E27',
            ],
            [
                'parent'    => 'Presiden Direktur',
                'name'      => 'SEVP CONSUMER BANKING',
                'code'      => 'A79',
            ],
            [
                'parent'    => 'Presiden Direktur',
                'name'      => 'SEVP Korporasi, Sindikasi dan Kelembagaan',
                'code'      => 'A84',
            ],
            [
                'parent'    => 'Presiden Direktur',
                'name'      => 'Divisi Audit intern',
                'code'      => 'A20',
            ],
        ];

        foreach ($vice as $val) {
            $org = OrgStruct::where('level', 'vice')->where('name', $val['name'])->first();
            $parent = OrgStruct::where('level', 'bod')->where('name', $val['parent'])->first();
            if (is_null($org)) {
                $org = new OrgStruct;
            }
            $org->level     = 'vice';
            $org->type      = 0;
            $org->parent_id = $parent ? $parent->id : null;
            $org->name      = $val['name'];
            $org->code      = $val['code'];
            $org->status    = 1;
            $org->city_id   = 264;
            $org->province_id = 15;
            $org->save();
        }
    }

    public function saveDivisi()
    {
        $divisi  = [
            [
                'parent'    => 'Presiden Direktur',
                'name'      => 'Corporate Secretary',
                'code'      => 'A28',
            ],
            [
                'parent'    => 'Presiden Direktur',
                'name'      => 'Yayasan Kesejahteraan Pegawai',
                'code'      => 'E19',
            ],
            [
                'parent'    => 'Presiden Direktur',
                'name'      => 'Unit Security Teknologi Informasi',
                'code'      => 'A62',
            ],
            [
                'parent'    => 'Presiden Direktur',
                'name'      => 'Unit Administrasi Kredit',
                'code'      => 'A57',
            ],
            [
                'parent'    => 'Presiden Direktur',
                'name'      => 'Unit Hubungan Kelembagaan',
                'code'      => 'A80',
            ],
            [
                'parent'    => 'Presiden Direktur',
                'name'      => 'DPP',
                'code'      => 'E11',
            ],
        ];

        foreach ($divisi as $val) {
            $org = OrgStruct::where('level', 'division')->where('name', $val['name'])->first();
            $parent = OrgStruct::where('level', 'bod')->where('name', $val['parent'])->first();
            if (is_null($org)) {
                $org = new OrgStruct;
            }
            $org->level     = 'division';
            $org->type      = 0;
            $org->parent_id = $parent ? $parent->id : null;
            $org->name      = $val['name'];
            $org->code      = $val['code'];
            $org->status    = 1;
            $org->city_id   = 264;
            $org->province_id = 15;
            $org->save();
        }
    }

    public function saveCabang()
    {
        $cabang  = [
            [
                'name'      => 'Cabang Banyuwangi',
                'parent'    => 'Direktur TI & Operasi',
                'code'      => 'C01',
                'phone'     => '123456',
                'address'   => 'Jl. Basuki Rahmat',
            ],
            [
                'name'      => 'Cabang Jember',
                'parent'    => 'Direktur TI & Operasi',
                'code'      => 'C02',
                'phone'     => '123456',
                'address'   => 'Jl. Basuki Rahmat',
            ],
            [
                'name'      => 'Cabang Malang',
                'parent'    => 'Direktur TI & Operasi',
                'code'      => 'C03',
                'phone'     => '123456',
                'address'   => 'Jl. Basuki Rahmat',
            ],
            [
                'name'      => 'Cabang Madiun',
                'parent'    => 'Direktur TI & Operasi',
                'code'      => 'C04',
                'phone'     => '123456',
                'address'   => 'Jl. Basuki Rahmat',
            ],
            [
                'name'      => 'Cabang Utama',
                'parent'    => 'Direktur TI & Operasi',
                'code'      => 'C05',
                'phone'     => '123456',
                'address'   => 'Jl. Basuki Rahmat',
            ],
            [
                'name'      => 'Cabang Batu',
                'parent'    => 'Direktur TI & Operasi',
                'code'      => 'C06',
                'phone'     => '123456',
                'address'   => 'Jl. Basuki Rahmat',
            ],
        ];

        foreach ($cabang as $val) {
            $org = OrgStruct::where('level', 'cabang')->where('name', $val['name'])->first();
            $parent = OrgStruct::where('level', 'bod')->where('name', $val['parent'])->first();
            if (is_null($org)) {
                $org = new OrgStruct;
            }
            $org->level     = 'cabang';
            $org->type      = 0;
            $org->parent_id = $parent ? $parent->id : null;
            $org->name      = $val['name'];
            $org->code      = $val['code'];
            $org->phone     = $val['phone'];
            $org->address   = $val['address'];
            $org->status    = 1;
            $org->city_id   = 264;
            $org->province_id = 15;
            $org->save();
        }
    }
}
