<?php

namespace App\Entities;

use App\Entities\Traits\RaidModel;
use App\Entities\Traits\Utilities;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory, RaidModel, Utilities;

    const BULAN_BY_ANGKA = [
        1   => 'Januari',
        2   => 'Februari',
        3   => 'Maret',
        4   => 'April',
        5   => 'Mei',
        6   => 'Juni',
        7   => 'Juli',
        8   => 'Agustus',
        9   => 'September',
        10  => 'Oktober',
        11  => 'November',
        12  => 'Desember',
    ];

    public function handleActivate($request)
    {
        DB::beginTransaction();
        try {
            // dd($request->all(), json_decode($this));
            $this->status = $this->status == 1 ? 0 : 1;
            $this->save();

            DB::commit();
            return response()->json(
                [
                    'success' => true,
                    'message' => $this->status == 1 ? 'Data successfully activated' : 'Data successfully deactivated',
                    'data'    => $this
                ]
            );
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ],
                500
            );
        }
    }

    public function toArray($append = true)
    {
        if (!$append) {
            return $this->attributes;
        }
        return array_merge($this->attributesToArray(), $this->relationsToArray());
    }

    public static function getStatus($key)
    {
        $data = [
            'new' => [
                'color' => 'danger',
                'label' => 'New',
            ],
            'draft' => [
                'color' => 'warning',
                'label' => 'Draft',
            ],
            'waiting.approval' => [
                'color' => 'primary',
                'label' => 'Waiting Approval',
            ],
            'waiting.review' => [
                'color' => 'primary',
                'label' => 'Waiting Review',
            ],
            'rejected' => [
                'color' => 'danger',
                'label' => 'Rejected',
            ],
            'approved' => [
                'color' => 'success',
                'label' => 'Approved',
            ],
            'completed' => [
                'color' => 'success',
                'label' => 'Completed',
            ],
            'upgraded' => [
                'color' => 'info',
                'label' => 'Upgraded',
            ],
            'active' => [
                'color' => 'success',
                'label' => 'Active',
            ],
            'nonactive' => [
                'color' => 'danger',
                'label' => 'Non Active',
            ],
            'valid' => [
                'color' => 'success',
                'label' => 'Valid',
            ],
            'invalid' => [
                'color' => 'danger',
                'label' => 'Invalid',
            ],
            'open' => [
                'color' => 'success',
                'label' => 'Open',
            ],
            'opened' => [
                'color' => 'success',
                'label' => 'Opened',
            ],
            'closed' => [
                'color' => 'info',
                'label' => 'Closed',
            ],
            'done' => [
                'color' => 'success',
                'label' => 'Done',
            ],
        ];

        $status = $data[$key] ?? ['color' => 'primary', 'label' => ucwords(str_replace('.', ' ', $key))];

        return static::makeLabel($status['label'], $status['color']);
    }

    public static function makeLabel($label, $color = 'primary', $params = [])
    {
        $default = [
            'class' => '',
            'attrs' => '',
            'style' => '',
        ];
        $params = array_merge($default, $params);
        return '<span data-short="' . $label . '" class="label label-' . $color . ' label-inline text-nowrap ' . $params['class'] . '" ' . $params['attrs'] . ' style="' . $params['style'] . '">' . $label . '</span>';
    }

    // Fungsi untuk menggabungkan tanggal dengan format khusus
    function getTanggalFormatMulaiSelesai($tanggal_awal, $tanggal_akhir)
    {
        $tahun_awal = date('Y', strtotime($tanggal_awal));
        $tahun_akhir = date('Y', strtotime($tanggal_akhir));

        $bulan_awal = date('m', strtotime($tanggal_awal));
        $bulan_akhir = date('m', strtotime($tanggal_akhir));

        $hari_awal = date('j', strtotime($tanggal_awal));
        $hari_akhir = date('j', strtotime($tanggal_akhir));

        return $tanggal_awal->translatedFormat('d M Y') . ' s/d ' . $tanggal_akhir->translatedFormat('d M Y');

        if ($tanggal_awal === $tanggal_akhir) {
            return $tanggal_awal->translatedFormat('d F Y');
        } elseif ($bulan_awal === $bulan_akhir && $tahun_awal === $tahun_akhir) {
            return $tanggal_awal->translatedFormat('d') . " - " . $tanggal_akhir->translatedFormat('d') . " " . $tanggal_awal->translatedFormat('F Y');
        } elseif ($tahun_awal === $tahun_akhir) {
            return $tanggal_awal->translatedFormat('d F') . " - " . $tanggal_akhir->translatedFormat('d F') . " " . $tanggal_awal->translatedFormat('Y');
        } else {
            return $tanggal_awal->translatedFormat('d F Y') . " - " . $tanggal_akhir->translatedFormat('d F Y');
        }
    }
}
