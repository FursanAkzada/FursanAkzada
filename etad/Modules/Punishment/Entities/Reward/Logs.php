<?php

namespace Modules\Punishment\Entities\Reward;

use App\Entities\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends BaseModel
{
    use HasFactory;

    protected $table = 'trans_reward_logs';
    protected $fillable = [
        'reward_id',
        'keterangan',
        'status',
        'is_active',
    ];

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class, 'reward_id');
    }

    public function statusRaw()
    {
        if (is_null($this)) {
            return '<span class="badge badge-secondary">Status Undefined</span>';
        }
        switch ($this->status) {
            case 'waiting.hc':
                return '<span class="badge badge-warning text-white">Waiting Approval</span></span>';

            case 'approved.hc':
                return '<span class="badge badge-success">Completed</span></span>';

            case 'rejected.hc':
                return '<span class="badge badge-danger">Tidak disetujui HC</span></span>';

            case 'closed':
                return '<span class="badge badge-danger">Pengajuan Berakhir</span></span>';

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
                return $this->type == 'logs' ? 'text-info' : 'text-danger';
                break;
        }
    }
}
