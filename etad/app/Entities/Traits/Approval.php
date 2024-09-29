<?php

namespace App\Entities\Traits;

use App\Entities\Approval as ApprovalModel;
use App\Entities\Group;
use Carbon\Carbon;
use Modules\Settings\Entities\Menu;

trait Approval
{
    public function generateApproval($code, $description = NULL)
    {
        // dd(14, $code);
        if (!$menu = Menu::where('code', $code)->first()) {
            return response()
                ->json(
                    [
                        'success' => false,
                        'message' => 'Flow Approval tidak dapat ditemukan',
                    ],
                    500
                );
        }
        if ($this->approvals(in_array($this->status, [1, 'submit', 'waiting', 'waiting.approval', 'completed']) ? false : true)->exists()) {
            $this->approvals(in_array($this->status, [1, 'submit', 'waiting', 'waiting.approval', 'completed']) ? false : true)->delete();
        }
        $orders = $menu->flows;
        // dd(28, $orders);
        $results = [];
        foreach ($orders as $key => $item) {
            $results[] = new ApprovalModel(
                [
                    'group_id' => $item->group_id,
                    'order' => $item->order,
                    'type' => $item->type,
                    'status' => 'draft',
                    'keterangan' => $description,
                    'is_upgrade' => in_array($this->status, [1, 'submit', 'waiting', 'waiting.approval', 'completed']) ? false : true,
                ]
            );
        }
        return $this->approvals()->saveMany($results);
    }

    public function resetStatusApproval()
    {
        return $this->approvals()
            ->update(
                [
                    'status'      => 'draft',
                    'is_upgrade'  => in_array($this->status, [1, 'submit', 'waiting', 'waiting.approval', 'completed']) ? false : true,
                    'keterangan' => null,
                    'approved_at' => null,
                    'position_id' => null,
                    'user_id'     => null,
                ]
            );
    }

    public function approval()
    {
        if (in_array($this->status, [1, 'submit', 'waiting', 'waiting.approval', 'rejected', 'completed'])) {
            return $this->morphOne(ApprovalModel::class, 'targetable')->where('is_upgrade', false);
        }else{
            return $this->morphOne(ApprovalModel::class, 'targetable')->where('is_upgrade', true);
        }
    }

    public function approvals()
    {
        if (in_array($this->status, [1, 'submit', 'waiting', 'waiting.approval', 'rejected', 'completed'])) {
            return $this->morphMany(ApprovalModel::class, 'targetable')->where('is_upgrade', false);
        }else{
            return $this->morphMany(ApprovalModel::class, 'targetable')->where('is_upgrade', true);
        }
    }

    public function approvalsAll()
    {
        return $this->morphOne(ApprovalModel::class, 'targetable')->where('is_upgrade', false);
    }

    public function rejected()
    {
        if (in_array($this->status, [1, 'submit', 'waiting', 'waiting.approval', 'completed'])) {
            return $this->approvals()->where([['status', 'rejected'],['is_upgrade', false]])->latest()->first();
        }else{
            return $this->approvals()->where([['status', 'rejected'],['is_upgrade', true]])->latest()->first();
        }
    }

    public function firstNewApproval()
    {
        if (in_array($this->status, [1, 'submit', 'waiting', 'waiting.approval', 'completed'])) {
            return $this->approvals()->whereStatus('draft')->where('is_upgrade', false)->orderBy('order')->first();
        }else{
            return $this->approvals()->whereStatus('draft')->where('is_upgrade', true)->orderBy('order')->first();
        }
    }

    public function firstNewApprovalGet($code)
    {
        if (in_array($this->status, [1, 'submit', 'waiting', 'waiting.approval', 'completed'])) {
            return $this->approval($code)
                ->where('is_upgrade', false)
                ->whereStatus('draft')
                ->orderBy('order')
                ->first();
        }else{
            return $this->approval($code)
                ->where('is_upgrade', true)
                ->whereStatus('draft')
                ->orderBy('order')
                ->first();
        }
    }


    /** Check auth user can action approval by specific module **/
    public function checkApproval()
    {
        if ($new = $this->firstNewApproval()) {
            $user = auth()->user();
            return $this->approvals()
                ->where('order', $new->order)
                ->whereStatus('draft')
                ->whereIn('group_id', $user->getGroupIds())
                ->first();
        }
        return false;
    }
}
