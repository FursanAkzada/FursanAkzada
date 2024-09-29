<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Carbon;

class Notification extends DatabaseNotification
{
    protected $guarded = [];

    protected $appends = [
        'show_module',
        'show_message',
    ];

    /** ACCESSOR **/
    public function getShowModuleAttribute()
    {
        $modules = \Base::getModules();
        return $modules[$this->module] ?? '[System]';
    }

    public function getShowMessageAttribute()
    {
        return $this->message;
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'notifiable_id');
    }

    public function creatorName()
    {
        if ($this->users) {
            return $this->users->name;
        }
        return isset($this->creator) ? $this->creator->name : '[System]';
    }

    public function updaterName()
    {
        return $this->updater ? $this->updater->name : '[System]';
    }

    public function creationDate()
    {
        $date = $this->updated_at ?: $this->created_at;
        return Carbon::parse($date)->translatedFormat('d M Y, H:i:s');
        // return Carbon::parse($date)->diffForHumans();
    }

    public function createdByRaw()
    {
        return '<div data-order="' . ($this->updated_at ?: $this->created_at) . '" class="text-left make-td-py-0">
                    <small>
                        <div class="text-nowrap">
                            <i data-toggle="tooltip" title="' . \Str::title($this->creatorName()) . '"
                                class="fa fa-user fa-fw fa-lg mr-2"></i>
                            ' . \Str::title($this->creatorName()) . '
                        </div>
                        <div class="text-nowrap">
                            <i data-toggle="tooltip" title="' . $this->created_at->translatedFormat('d M Y, H:i:s') . '"
                                class="fa fa-clock fa-fw fa-lg mr-2"></i>
                            ' . $this->creationDate() . '
                        </div>
                    </small>
                </div>';
    }
}
