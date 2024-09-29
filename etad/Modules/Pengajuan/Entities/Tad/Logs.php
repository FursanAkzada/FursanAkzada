<?php

namespace Modules\Pengajuan\Entities\Tad;

use App\Entities\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends BaseModel
{
    use HasFactory;

    protected $table = 'trans_pengajuan_tad_logs';
    protected $fillable = [
        'pengajuan_id',
        'keterangan',
        'status',
        'is_active',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(
            Pengajuan::class,
            'pengajuan_id'
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
            case 'waiting.hc':
                return '<span class="badge badge-warning text-white">Waiting Approval</span></span>';
            case 'approved.hc':
                return '<span class="badge badge-success">Completed</span></span>';
            case 'rejected.hc':
                return '<span class="badge badge-danger">Tidak disetujui HC</span></span>';
            case 'waiting.vendor':
                return '<span class="badge badge-primary">Sedang Diproses Vendor</span></span>';
            case 'submitted.vendor':
                return '<span class="badge badge-success">Vendor Submitted</span></span>';
            case 'verification.hc':
                return '<span class="badge badge-warning text-white">Sedang diverifikasi HC</span></span>';
            case 'verificated.hc':
                return '<span class="badge badge-success">Sudah diverifikasi HC</span></span>';
            case 'rewaiting.vendor':
                return '<span class="badge badge-primary" data-toggle="tooltip"
                title="Beberapa Kandidat belum memenuhi kriteria Bank Jatim sehingga Vendor perlu menyiapkan kembali kandidat">Proses Ulang Sebagian</span></span>';
            case 'reprocess.vendor':
                return '<span class="badge badge-primary" data-toggle="tooltip"
                title="Kandidat belum memenuhi kriteria Bank Jatim sehingga Vendor perlu menyiapkan kembali kandidat">Proses Ulang Semua</span></span>';
            case 'closed':
                return '<span class="badge badge-secondary">Pengajuan Berakhir</span></span>';
            case 'draft':
                return '<span class="badge badge-warning text-white">Draft</span></span>';
            default:
                # code...
                break;
        }
    }

    public function classLogs()
    {
        $status = strtok($this->status, '.');
        switch ($status) {
            case 'waiting':
                return 'text-warning';

            case 'approved':
                return 'text-success';

            case 'submitted':
                return 'text-info';

            case 'verification':
                return 'text-warning';

            case 'verificated':
                return 'text-success';

            case 'rejected':
                return 'text-danger';

            case 'closed':
                return 'text-danger';

            default:
                return $this->status == 'logs' ? 'text-info' : 'text-danger';
        }
    }
}
