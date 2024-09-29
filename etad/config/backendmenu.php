<?php

return [
    [
        'section' => 'NAVIGASI',
        'title' => 'NAVIGASI',
        'name' => 'navigasi',
        'perms' => 'dashboard',
    ],
    // Dashboard
    [
        'name' => 'dashboard',
        'perms' => 'dashboard',
        'title' => 'Dashboard',
        'icon' => 'fa fa-th-large',
        'url' => '/dashboard',
    ],
    // Audit Plan
    [
        'name' => 'pengajuan',
        'title' => 'Pengajuan',
        'icon' => 'fa fa-user-edit',
        'submenu' => [
            [
                'name' => 'pengajuan.tad.form',
                'perms' => 'pengajuan.tad.form',
                'title' => 'Pengajuan',
                // 'url' => route('pengajuan.pengajuan.index'),
                'url'   => '/pengajuan/pengajuan',
            ],
            [
                'name' => 'pengajuan.tad.kandidat',
                'perms' => 'pengajuan.tad.kandidat',
                'title' => 'Kandidat',
                // 'url' => route('pengajuan.kandidat.index'),
                'url'   => '/pengajuan/kandidat',
            ],
            [
                'name' => 'pengajuan.tad.wawancara',
                'perms' => 'pengajuan.tad.wawancara',
                'title' => 'Wawancara',
                // 'url' => route('pengajuan.wawancara.index'),
                'url'   => '/pengajuan/wawancara',
            ],
            [
                'name' => 'pengajuan.tad.penerimaan',
                'perms' => 'pengajuan.tad.penerimaan',
                'title' => 'Penerimaan',
                // 'url' => route('pengajuan.penerimaan.index'),
                'url'   => '/pengajuan/penerimaan',
            ],
        ]
    ],

    [
        'name' => 'resign_mutasi',
        'title' => 'Resign & Mutasi',
        'icon' => 'fas fa-clipboard-list',
        'submenu' => [
            [
                'name' => 'resign_mutasi.resign',
                'perms' => 'resign_mutasi.resign',
                'title' => 'Resign',
                // 'url' => route('pengajuan.resign.index'),
                'url'   => '/pengajuan/resign',
            ],
            [
                'name' => 'resign_mutasi.mutasi',
                'perms' => 'resign_mutasi.mutasi',
                'title' => 'Mutasi',
                // 'url' => route('pengajuan.mutasi.index'),
                'url'   => '/pengajuan/mutasi',
            ],
        ]
    ],

    [
        'name' => 'personil',
        'title' => 'Personil & Quota',
        'icon' => 'fas fa-id-card',
        'submenu' => [
            [
                'name' => 'personil.migrasi',
                'perms' => 'personil.migrasi',
                'title' => 'Migrasi Personil',
                // 'url' => route('pengajuan.index'),
                'url'   => '/personil/migrasi',
            ],
            [
                'name' => 'personil.aktif',
                'perms' => 'personil.aktif',
                'title' => 'Personil Bekerja',
                // 'url' => route('pengajuan.-aktif.index'),
                'url'   => '/personil/aktif',
            ],
            // [
            //     'name' => 'personil.nonaktif',
            //     'perms' => 'personil.nonaktif',
            //     'title' => 'Personil Nonaktif',
            //     // 'url' => route('pengajuan.-nonaktif.index'),
            //     'url'   => '/pengajuan/personil-nonaktif',
            // ],
            [
                'name' => 'personil.unemployed',
                'perms' => 'personil.unemployed',
                'title' => 'Personil Belum Bekerja',
                // 'url' => route('pengajuan.-unemployed.index'),
                'url'   => '/personil/belum-bekerja',
            ],
            [
                'name' => 'personil.failed',
                'perms' => 'personil.failed',
                'title' => 'Gagal Upload',
                // 'url' => route('personil.gagal.index'),
                'url'   => '/personil/gagal',
            ],
            [
                'name' => 'personil.quota',
                'perms' => 'personil.quota',
                'title' => 'Quota',
                // 'url' => route('personil.quota.index'),
                'url'   => '/personil/quota',
            ],
        ]
    ],

    [
        'name' => 'penilaian',
        'title' => 'Penilaian',
        'icon' => 'fas fa-star',
        'submenu' => [
            [
                'name' => 'penilaian.vendor',
                'perms' => 'penilaian.vendor',
                'title' => 'Vendor',
                // 'url' => route('penilaian.vendor.index'),
                'url'   => '/penilaian/vendor',
            ],
            [
                'name' => 'penilaian.tad',
                'perms' => 'penilaian.tad',
                'title' => 'Tenaga Ahli Daya',
                // 'url' => route('penilaian.tad.form.index'),
                'url'   => '/penilaian/tad',
            ],
            [
                'name' => 'penilaian.perpanjangan',
                'perms' => 'penilaian.perpanjangan',
                'title' => 'Perpanjangan Kontrak',
                // 'url' => route('penilaian.perpanjangan.index'),
                'url'   => '/penilaian/perpanjangan',
            ],
        ]
    ],

    [
        'name' => 'pu',
        'title' => 'Penghargaan & Pembinaan',
        'icon' => 'fas fa-medal',
        'submenu' => [
            [
                'name' => 'pu.reward',
                'perms' => 'pu.reward',
                'title' => 'Penghargaan',
                // 'url' => route('reward.form.index'),
                'url'   => '/pu/reward/form',
            ],
            [
                'name' => 'pu.pembinaan',
                'perms' => 'pu.pembinaan',
                'title' => 'Pembinaan',
                // 'url' => route('pembinaan.form.index'),
                'url'   => '/pu/pembinaan/form',
            ],
        ]
    ],

    // Admin Console
    [
        'section' => 'ADMIN KONSOL',
        'name' => 'console_admin',
    ],
    [
        'name' => 'master',
        'perms' => 'master',
        'title' => 'Parameter',
        'icon' => 'fa fa-database',
        'submenu' => [
            [
                'name' => 'master.so',
                'title' => 'Struktur Organisasi',
                'submenu' => [
                    [
                        'name' => 'master.so.root',
                        'title' => 'Perseroan',
                        'url' => '/master/so/root'
                    ],
                    [
                        'name' => 'master.so.direksi',
                        'title' => 'Direksi',
                        'url' => '/master/so/direksi',
                    ],
                    [
                        'name' => 'master.so.vice',
                        'title' => 'SEVP',
                        'url' => '/master/so/vice',
                    ],
                    [
                        'name' => 'master.so.divisi',
                        'title' => 'Divisi',
                        'url' => '/master/so/divisi',
                    ],
                    [
                        'name' => 'master.so.sub-divisi',
                        'title' => 'Sub Divisi',
                        'url' => '/master/so/departemen',
                    ],
                    [
                        'name' => 'master.so.cabang',
                        'title' => 'Cabang',
                        'url' => '/master/so/cabang',
                    ],
                    [
                        'name' => 'master.so.cabang-pembantu',
                        'title' => 'Cabang Pembantu',
                        'url' => '/master/so/cabang-pembantu',
                    ],
                    [
                        'name' => 'master.so.kantor-kas',
                        'title' => 'Kantor Kas',
                        'url' => '/master/so/kantor-kas',
                    ],
                    [
                        'name' => 'master.so.position',
                        'title' => 'Jabatan',
                        'url' => '/master/so/jabatan',
                    ],
                ]
            ],
            [
                'name' => 'master.geografis',
                'title' => 'Geografis',
                'submenu' => [
                    [
                        'name' => 'master.geografis.provinsi',
                        'title' => 'Provinsi',
                        'url' => '/master/geografis/provinsi'
                    ],
                    [
                        'name' => 'master.geografis.kab-kota',
                        'title' => 'Kota / Kabupaten',
                        'url' => '/master/geografis/kab-kota'
                    ],
                ]
            ],
            [
                'name' => 'master',
                'title' => 'Vendor',
                'submenu' => [
                    [
                        'name' => 'master.vendor',
                        'title' => 'Vendor',
                        'url' => '/master/vendor',
                    ],
                    [
                        'name' => 'master.kategori-vendor',
                        'title' => 'Kategori TAD',
                        'url' => '/master/kategori-vendor',
                    ],
                    [
                        'name' => 'master.jabatan-tad',
                        'title' => 'Posisi TAD',
                        'url' => '/master/jabatan-tad',
                    ],
                ]
            ],
            [
                'name' => 'master',
                'title' => 'Pertanyaan',
                'submenu' => [
                    [
                        'name' => 'master.pertanyaan.kategori',
                        'title' => 'Kategori Pertanyaan',
                        'url' => '/master/pertanyaan/kategori',
                    ],
                    [
                        'name' => 'master.pertanyaan.tad',
                        'title' => 'Pertanyaan TAD',
                        'url' => '/master/pertanyaan/tad',
                    ],
                    [
                        'name' => 'master.pertanyaan.vendor',
                        'title' => 'Pertanyaan Vendor',
                        'url' => '/master/pertanyaan/vendor',
                    ],
                ]
            ],
            [
                'name' => 'master.wawancara',
                'title' => 'Wawancara',
                'submenu' => [
                    [
                        'name' => 'master.wawancara.kompetensi',
                        'title' => 'Kompetensi',
                        'url' => '/master/wawancara/kompetensi',
                    ],
                    [
                        'name' => 'master.wawancara.pertanyaan',
                        'title' => 'Pertanyaan',
                        'url' => '/master/wawancara/pertanyaan',
                    ],
                ]
            ],
            [
                'name' => 'master',
                'title' => 'Resign',
                'submenu' => [
                    [
                        'name' => 'master.reason-resign',
                        'title' => 'Alasan Resign',
                        'url' => '/master/reason-resign',
                    ],
                ]
            ],
            [
                'name' => 'master',
                'title' => 'Reward & Pembinaan',
                'submenu' => [
                    [
                        'name' => 'master.rp.reward',
                        'title' => 'Jenis Reward',
                        'url' => '/master/rp/reward',
                    ],
                    [
                        'name' => 'master.rp.pembinaan',
                        'title' => 'Jenis Pembinaan',
                        'url' => '/master/rp/pembinaan',
                    ],
                ]
            ],
            [
                'name' => 'master',
                'title' => 'Pendidikan',
                'submenu' => [
                    [
                        'name' => 'master.pendidikan',
                        'title' => 'Pendidikan',
                        'url' => '/master/pendidikan',
                    ],
                    [
                        'name' => 'master.jurusan',
                        'title' => 'Jurusan',
                        'url' => '/master/jurusan',
                    ],
                ]
            ],
        ]
    ],
    [
        'name' => 'setting',
        'perms' => 'setting',
        'title' => 'Konfigurasi',
        'icon' => 'fa fa-cogs',
        'submenu' => [
            [
                'name' => 'setting.roles',
                'title' => 'Hak Akses',
                'url' => '/settings/roles',
            ],
            [
                'name' => 'setting.user',
                'title' => 'Manajemen User',
                'url' => '/settings/user',
            ],
            [
                'name' => 'setting.user-vendor',
                'title' => 'User Vendor',
                'url' => '/settings/user-vendor',
            ],
            [
                'name' => 'setting.flow',
                'title' => 'Flow Approval',
                'url' => '/settings/flow',
            ],
            [
                'name' => 'setting.audit-trail',
                'title' => 'Audit Trail',
                'url' => '/settings/audit-trail',
            ],
        ]
    ],
];
