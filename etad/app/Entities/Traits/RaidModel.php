<?php

namespace App\Entities\Traits;

use App\Entities\Activity;
use App\Entities\User;
use App\Libraries\Encryption;
use DB;

use Illuminate\Support\Carbon;
use Modules\Master\Entities\SO\OrgStruct;

trait RaidModel
{
    protected $module = null;
    protected $preventAttrSet = false;

    public static function boot()
    {
        parent::boot();

        if (auth()->check()) {
            if (\Schema::hasColumn(with(new static)->getTable(), 'updated_by')) {
                static::saving(function ($table) {
                    $table->updated_by = auth()->user()->id;
                });
            }

            if (\Schema::hasColumn(with(new static)->getTable(), 'created_by')) {
                static::creating(function ($table) {
                    $table->updated_by = null;
                    // $table->updated_at = null;
                    $table->created_by = auth()->user()->id;
                });
            }
        }

        if ($log_table = (new static)->log_table) {
            static::saved(function ($table) {
                $log = $table->attributes;
                $log[$table->log_table_fk] = $log['id'];
                unset($log['id']);

                DB::table($table->log_table)->insert($log);
            });

            static::deleting(function ($table) {
                $log = $table->attributes;
                $log[$table->log_table_fk] = $log['id'];
                unset($log['id']);

                DB::table($table->log_table)->insert($log);
            });
        }

        // /* Audit Trail */
        self::created(function ($model) {
            if (!\Schema::hasColumn(with(new static)->getTable(), 'module') && !is_null($model->module) && auth()->check()) {
                auth()->user()->storeLog($model->module, 'create', $model->getAttributes());
            }
        });
        self::updating(function ($model) {
            $old = $model->replicate();
            $old->preventAttrSet = true;
            $dirty = $model->getDirty();
            foreach ($dirty as $field => $newdata) {
                $olddata = $model->getOriginal($field);
                if ($olddata != $model->getAttributes()[$field]) {
                    $old->$field = $olddata;
                }
            }
            if (!is_null($model->module) && auth()->check()) {
                auth()->user()->storeLog($model->module, 'update', $model->getAttributes(), $old->getAttributes());
            }
        });
        self::deleting(function ($model) {
            if (!is_null($model->module)) {
                auth()->user()->storeLog($model->module, 'delete', null, $model->getAttributes());
            }
        });
    }

    /*-----------*/

    /* Encrypt & Decrypt ID */
    public function getIdxAttribute($value)
    {
        return Encryption::encode($this->id);
    }
    public static function findx($id)
    {
        return static::find(Encryption::decode($id));
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'publish_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function entryBy()
    {
        return isset($this->creator) ? $this->creator->name : '[System]';
    }

    public function publishBy()
    {
        return isset($this->publisher) ? $this->publisher->name : '[System]';
    }

    public function creationDate()
    {
        $date = $this->updated_at ?: $this->created_at;
        return Carbon::parse($date)->translatedFormat('d M Y, H:i:s');
    }

    public function creatorName()
    {
        if ($this->updater) {
            return $this->updater->name;
        }

        return isset($this->creator) ? $this->creator->name : '[System]';
    }


    public function updaterName()
    {
        return isset($this->updater) ? $this->updater->name : '[System]';
    }

    public function publisherName()
    {
        return isset($this->publisher) ? $this->publisher->name : '[System]';
    }

    /* save data */
    public static function saveData($request, $identifier = 'id')
    {
        $record = static::prepare($request, $identifier);
        $record->fill($request);
        $record->save();

        return $record;
    }

    public static function prepare($request, $identifier = 'id')
    {
        $record = new static;

        if ($request->has($identifier) && $request->get($identifier) != null && $request->get($identifier) != 0) {
            $record = static::find($request->get($identifier));
        }

        return $record;
    }

    public function scopeKeywordBy($query, $column)
    {
        return $query->when(
            $keyword = request()->post('keyword'),
            function ($q) use ($column, $keyword) {
                $q->whereLike($column, $keyword);
            }
        );
    }

    // Activity
    public function logs_activity()
    {
        return $this->morphMany(Activity::class, 'target');
    }

    public function addLog($message)
    {
        $log = new Activity;
        $log->fill(
            [
                'message' => $message,
                'module' => request()->get('module'),
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
            ]
        );
        $this->logs_activity()->save($log);
    }

    public function getNameRoot()
    {
        $root = OrgStruct::where('level', 'root')->first();
        return $root->name;
    }

    // Fungsi untuk menggabungkan tanggal dengan format khusus
    function getTanggalFormatMulaiSelesai($tanggal_awal, $tanggal_akhir, $separation = " s/d ", $summary = false)
    {
        $tahun_awal = date('Y', strtotime($tanggal_awal));
        $tahun_akhir = date('Y', strtotime($tanggal_akhir));

        $bulan_awal = date('m', strtotime($tanggal_awal));
        $bulan_akhir = date('m', strtotime($tanggal_akhir));

        $hari_awal = date('j', strtotime($tanggal_awal));
        $hari_akhir = date('j', strtotime($tanggal_akhir));

        return $tanggal_awal->translatedFormat('d M Y') . $separation . $tanggal_akhir->translatedFormat('d M Y');

        if ($tanggal_awal === $tanggal_akhir) {
            if($summary){
                return $tanggal_awal->translatedFormat('M Y');
            }
            return $tanggal_awal->translatedFormat('d M Y');
        } elseif ($bulan_awal === $bulan_akhir && $tahun_awal === $tahun_akhir) {
            if($summary){
                return $tanggal_awal->translatedFormat('M Y');
            }
            return $tanggal_awal->translatedFormat('d') . $separation . $tanggal_akhir->translatedFormat('d') . " " . $tanggal_awal->translatedFormat('M Y');
        } elseif ($tahun_awal === $tahun_akhir) {
            if($summary){
                return $tanggal_awal->translatedFormat('F') . $separation . $tanggal_akhir->translatedFormat('F') . " " . $tanggal_awal->translatedFormat('Y');
            }
            return $tanggal_awal->translatedFormat('d F') . $separation . $tanggal_akhir->translatedFormat('d F') . " " . $tanggal_awal->translatedFormat('Y');
        } else {
            if($summary){
                return $tanggal_awal->translatedFormat('M Y') . $separation . $tanggal_akhir->translatedFormat('M Y');
            }
            return $tanggal_awal->translatedFormat('d M Y') . $separation . $tanggal_akhir->translatedFormat('d M Y');
        }
    }
}
