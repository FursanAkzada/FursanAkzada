<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Master\Entities\Tad\Tad;


class ApiController extends Controller
{
    function personil(Request $request)
    {
        return Tad::with(
            [
                'agama' => function ($q) {
                    $q->select('Sandi', 'Lengkap');
                },
                'city' => function ($q) {
                    $q
                        ->select('id', 'province_id', 'name', 'code')
                        ->with(
                            [
                                'province' => function ($q) {
                                    $q->select('id', 'name', 'code');
                                }
                            ]
                        );
                },
                'pendidikan' => function ($q) {
                    $q->select('sandi', 'lengkap');
                },
                'jurusan' => function ($q) {
                    $q->select('id', 'name', 'pendidikan_id')
                        ->with(
                            [
                                'pendidikan'    => function ($q) {
                                    $q->select('id', 'name');
                                }
                            ]
                        );
                },
                'jabatan' => function ($q) {
                    $q->select('idunit', 'NM_UNIT', 'kategori_id')
                        ->with(
                            [
                                'kategori'    => function ($q) {
                                    $q->select('id', 'nama');
                                }
                            ]
                        );
                },
                'vendor' => function ($q) {
                    $q->select('id', 'nama');
                },
                'keluarga',
                'files',

                // 'wawancara',
                'kepegawaian'   => function ($q) {
                    $q->with(
                        [
                            'unitKerja',
                            'vendor',
                            'jabatan',
                        ]
                    );
                },
                'kepegawaianTerahir',
                'riwayatKepegawaian',

                'penilaian',
                // perpanjangankotrak

                'reward',
                'pembinaan',
            ]
        )
            ->paginate($request->per_page ?? 15);
    }
}
