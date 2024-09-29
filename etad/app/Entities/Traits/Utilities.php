<?php
namespace App\Entities\Traits;

use Carbon\Carbon;

trait Utilities
{
    public function lpad($field, $length = 2, $padder = ' ')
    {
        return str_pad($this->$field, $length, $padder, STR_PAD_LEFT);
    }

    public function labelStatus($status = null)
    {
        return \Base::getStatus($status ?? $this->status);
    }

    public function readMoreRaw($value, $maxLength = 150)
    {
        $return = $value;
        if (strlen($value) > $maxLength) {
            $return   = substr($value, 0, $maxLength);
            $readmore = substr($value, $maxLength);

            $return .= '<a href="javascript: void(0)" class="read-more" onclick="$(this).parent().find(\'.read-more-cage\').show(); $(this).hide()">&nbsp;&nbsp;read more</a>';

            $readless = '<a href="javascript: void(0)" class="read-less" onclick="$(this).parent().parent().find(\'.read-more\').show(); $(this).parent().hide()">&nbsp;&nbsp;read less</a>';

            $return = "<span>{$return}<span style='display: none' class='read-more-cage'>{$readmore} {$readless}</span></span>";
        }

        return $return;
    }

    public function readMoreText($field, $maxLength = 150)
    {
        $value = $this->$field;
        return utf8_decode($this->readMoreRaw($value, $maxLength));
    }

    public static function options($display, $id = 'id', $params = [], $default=null)
    {
        $q = static::select('*');

        $params = array_merge([
            'valuePrefix' => '',
        ], $params);

        if (isset($params['filters'])) {
            foreach ($params['filters'] as $key => $value) {
                if (is_numeric($key) && is_callable($value)) {
                    $q = $q->where($value);
                } else {
                    $q = $q->where($key, $value);
                }
            }
        }

        if (isset($params['orders'])) {
            foreach ($params['orders'] as $key => $value) {
                if (is_numeric($key)) {
                    $key   = $value;
                    $value = 'asc';
                }

                $q = $q->orderBy($key, $value);
            }
        }

        $r = [];

        $ret = '';
        if ($default !== false) {
            if($default === null){
                $default = '(Pilih Salah Satu)';
            }
            $ret = '<option value="">' . $default . '</option>';
        }

        if (is_string($display)) {
            $q = $q->orderBy($display, 'asc');
            $r = $q->pluck($display, $id);

            foreach ($r as $i => $v) {
                $i = $params['valuePrefix'] . $i;
                $checked = isset($params['selected']) &&
                           (is_array($params['selected']) ? in_array($i, $params['selected']) : $i == $params['selected']);
                if ($checked) {
                    $ret .= '<option value="' . $i . '" selected>' . $v . '</option>';
                } else {
                    $ret .= '<option value="' . $i . '">' . $v . '</option>';
                }
            }
        } elseif (is_callable($display)) {
            $r = $q->get();

            foreach ($r as $d) {
                $i = $params['valuePrefix'] . $d->$id;
                $checked = isset($params['selected']) &&
                           (is_array($params['selected']) ? in_array($i, $params['selected']) : $i == $params['selected']);
                if ($checked) {
                    $ret .= '<option value="' . $i . '" selected>' . $display($d) . '</option>';
                } else {
                    $ret .= '<option value="' . $i . '">' . $display($d) . '</option>';
                }
            }
        }
        return $ret;
    }

    public static function queryRaw($query)
    {
        $q = static::select('*');

        $q->from(\DB::raw("($query) tbl"));

        return $q->get();
    }

    public function getCreatorName()
    {
        if ($this->updater) {
            return $this->updater->name;
        }
        return isset($this->creator) ? $this->creator->name : '[System]';
    }

    public function getUpdaterName()
    {
        return $this->updater ? $this->updater->name : '[System]';
    }

    public function getCreationDate()
    {
        $date = $this->updated_at ?: $this->created_at;
        return Carbon::parse($date)->translatedFormat('d M Y, H:i:s');
    }

    public function createdByRaw()
    {
        return '<div data-order="' . ($this->updated_at ?: $this->created_at) . '" class="text-left make-td-py-0">
                <small>
                    <div class="text-nowrap">
                        <i data-toggle="tooltip" title="' . \Str::title($this->creatorName()) . '"
                            class="fa fa-user fa-fw fa-lg mr-2"></i>
                        ' . \Str::title($this->getCreatorName()) . '
                    </div>
                    <div class="text-nowrap">
                        <i data-toggle="tooltip" title="' . $this->created_at->translatedFormat('d M Y, H:i:s') . '"
                            class="fa fa-clock fa-fw fa-lg mr-2"></i>
                        ' . $this->getCreationDate() . '
                    </div>
                </small>
            </div>';
    }
    public function getDuration()
    {
        if (!$this->created_at) {
            return '-';
        }

        $now = Carbon::now();
        $durasi = $now->diff($this->created_at);

        $tahun = $durasi->y;
        $bulan = $durasi->m;
        $hari = $durasi->d;
        $jam = $durasi->h;
        $menit = $durasi->i;
        $detik = $durasi->s;

        $durasiString = '';

        if ($tahun > 0) {
            $durasiString .= $tahun . ' tahun' . ($tahun > 1 ? '' : '') . ' ';
        }

        if ($bulan > 0) {
            $durasiString .= $bulan . ' bulan' . ($bulan > 1 ? '' : '') . ' ';
        }

        if ($hari > 0) {
            $durasiString .= $hari . ' hari' . ($hari > 1 ? '' : '') . ' ';
        }
        if ($jam > 0) {
            $durasiString .= $jam . ' Jam' . ($jam > 1 ? '' : '') . ' ';
        }

        if ($menit > 0) {
            $durasiString .= $menit . ' menit' . ($menit > 1 ? '' : '') . ' ';
        }

        if ($detik >= 0) {
            $durasiString .= $detik . ' detik yang lalu' . ($detik > 1 ? '' : '') . ' ';
        }

        return trim($durasiString);
    }
    public function createdByRaw2(){
        return '<div data-order="' . ($this->updated_at ?: $this->created_at) . '" class="text-left make-td-py-0">
                <small>
                    <div class="text-nowrap">
                        <i data-toggle="tooltip" title="' . \Str::title($this->creatorName()) . '"
                            class="fa fa-user fa-fw fa-lg mr-2"></i>
                        ' . \Str::title($this->getCreatorName()) . '
                    </div>
                    <div class="text-nowrap">
                        <i data-toggle="tooltip" title="' . $this->created_at->translatedFormat('d M Y, H:i:s') . '"
                            class="fa fa-clock fa-fw fa-lg mr-2"></i>
                        ' . $this->getCreationDate() . '
                    </div>
                </small>
            </div>';
    }
}
