<?php

namespace Modules\Pengajuan\Entities\Tad;

use App\Entities\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuotaLog extends BaseModel
{
    use HasFactory;

    protected $table = 'trans_pengajuan_tad_quota_periode_logs';
    protected $fillable = [
        'status',
        'is_active',
        'pengajuan_tad_quota_periode_id',
        'keterangan',
    ];

    public function quotaPeriode()
    {
        return $this->belongsTo(
            QuotaPeriode::class,
            'pengajuan_tad_quota_periode_id'
        );
    }

    public function statusRaw()
    {
        if (is_null($this)) {
            return '<span class="badge badge-secondary">Status Undefined</span>';
        }
        switch ($this->status) {
            case 'draft':
                return '<span class="badge badge-warning text-white">DRAFT</span></span>';
            case 'submit':
                return '<span class="badge badge-primary text-white">Waiting Approval</span></span>';
            case 'approved':
                return '<span class="badge badge-success">Completed</span></span>';
            case 'rejected':
                return '<span class="badge badge-danger">Ditolak</span></span>';
            case 'new-struct':
                return '<span class="badge badge-warning text-white">Struktur Baru</span></span>';
            case 'new-position':
                return '<span class="badge badge-warning text-white">Posisi TAD Baru</span></span>';
            default:
                return $this->status;
                break;
        }
    }

    public function classLogs()
    {
        $status = strtok($this->status, '.');
        switch ($status) {
            case 'draft':
            case 'new-struct':
            case 'new-position':
                return 'text-warning';
            case 'submit':
                return 'text-primary';
            case 'approve':
            case 'approved':
                return 'text-success';
            case 'reject':
            case 'rejected':
                return 'text-danger';
            default:
                return $this->status == 'logs' ? 'text-info' : 'text-danger';
        }
    }
}
