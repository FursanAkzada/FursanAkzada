<?php

namespace App\Http\Controllers;

use App\Entities\Notification;
use App\Entities\SysFile;
use App\Entities\TempFiles;
use App\Entities\User;
use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Vendor;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;

class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getTempFiles()
    {
        $temp = TempFiles::all()->last();
        return $temp;
    }
    public function userNotificationRead(Notification $notification)
    {
        $notification->read_at = now();
        $notification->save();
        // dd(json_decode($notification), $notification->data['link']);
        return redirect($notification->data['link']);
    }
    public function saveTempFiles(Request $request)
    {
        \DB::beginTransaction();
        try {
            if ($file = $request->file('file')) {
                $file_path = str_replace('.' . $file->extension(), '', $file->getClientOriginalName());
                $file_path .= '-' . time() . '.' . $file->getClientOriginalExtension();

                $temp = new TempFiles;
                $temp->name = $file->getClientOriginalName();
                $temp->path = $file->storeAs('temp-files', $file_path, 'public');
                // $temp->file_type = $file->extension();
                $temp->size = $file->getSize();
                $temp->flag = $request->flag;
                $temp->save();

                \DB::commit();
                return response()->json(
                    [
                        'file' => TempFiles::find($temp->id)
                    ]
                );
            }
            return response()->json(
                [
                    'success' => false,
                    'message' => 'File not found',
                    'trace'   => '',
                ],
                422
            );
        } catch (\Exception $e) {
            \DB::rollback();
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

    public function selectVendor($search, Request $request)
    {
        $items = Vendor::keywordBy('nama')->orderBy('nama');
        switch ($search) {
            case 'all':
                $items = $items;
                break;

            default:
                $items = $items->whereNull('id');
                break;
        }
        $items = $items->paginate();
        return $this->responseSelect2($items, 'nama', 'id');
    }

    function getQuotaCount(Request $request)
    {
        $PERIODE        = QuotaPeriode::with(
            []
        )
            ->when(
                $year = request()->year,
                function ($q) use ($year) {
                    $q->where('year', $year);
                }
            )
            ->when(
                $semester = request()->semester,
                function ($q) use ($semester) {
                    if ($semester === 'Satu') {
                        $q->where('semester', $semester);
                    } else if ($semester === 'Dua') {
                        $q->where('semester', $semester);
                    } else {
                    }
                }
            )
            ->get();
        $quota_count        = 0;
        $fulfillment_count  = 0;
        foreach ($PERIODE as $periode) {
            $quota_count        += $periode->quota;
            $fulfillment_count  += $periode->fulfillment;
        }
        return response()->json(
            [
                'quota_count'        => $quota_count,
                'fulfillment_count'  => $fulfillment_count,
            ]
        );
    }
}
