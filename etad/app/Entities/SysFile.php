<?php

namespace App\Entities;

use App\Entities\BaseModel;
use App\Entities\Log\LogSysFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


// models
use App\Models\Auth\User;

class SysFile extends BaseModel
{
    // use SoftDeletes;

    /* Attributes Model */
    protected $table  = 'sys_files';
    // protected $log_table = 'log_sys_files';

    protected $fillable     = [
        'target_id',
        'target_type',
        'module',
        'gs_file_name',
        'file_name',
        'file_path',
        'file_size',
        'flag', //Tambahkan flag jika ada kondisi tertentu
    ];

    protected $appends = [
        'file_url',
        'file_type'
    ];
    public function target()
    {
        return $this->morphTo();
    }

    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            // Handle jika ada yg menyimpan full url
            $file_path = explode('/storage/', $this->file_path);
            $file_path = end($file_path);
            return url('storage/'.$file_path);
        }
        return null;
    }

    public function getFileTypeAttribute()
    {
        if ($this->file_name) {
            $extension = explode('.', trim($this->file_name));
            return strtolower(end($extension));
        }
        return null;
    }

    public function getFileIconAttribute()
    {
        $icon = 'far fa-file-alt';
        $type = $this->file_type;
        if ($type == 'pdf') {
            $icon = 'text-danger far fa-file-pdf';
        } else if ($type == 'xlsx') {
            $icon = 'text-success far fa-file-excel';
        } else if ($type == 'jpg' || $type == 'png') {
            $icon = 'text-warning far fa-file-image';
        } else if ($type == 'ppt') {
            $icon = 'text-danger far fa-file-powerpoint';
        } else if ($type == 'docx') {
            $icon = 'text-primary far fa-file-word';
        }
        return $icon . ' ' . $type;
    }
}
