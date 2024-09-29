<?php

namespace Modules\Settings\Http\Controllers\Profiles;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Contracts\Support\Renderable;

class NotificationController extends Controller
{
    protected $module = 'settings';
    protected $route  = 'settings.notification';
    protected $view   = 'settings::notification';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'title'  => 'Notifikasi',
                'breadcrumb' => [
                    'Notifikasi' => '/',
                ]
            ]
        );
    }

    public function grid()
    {
        $records = auth()->user()->notifications()->when($keyword = request()->module_name, function ($q) use ($keyword) {
            $q->whereLike(['data'],$keyword . '.');
        });
        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'module',
                function ($record) {
                    return Str::of($record->data['type'])->replace('.', ' ')->title();
                }
            )
            ->addColumn(
                'message',
                function ($record) {
                    return "<b>". $record->data['title'] .'</b><br>'. $record->data['message'];
                }
            )
            ->addColumn(
                'created_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
            ->rawColumns(['action', 'created_by', 'message'])
            ->make(true);
    }

    public function index()
    {
        $this->setPrepare(
            [
                // 'perms' => $this->perms.'.index',
                // 'tableStruct' => [
                //     'datatable_1' => [
                //         $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                //         $this->makeColumn('name:title|label:Notifikasi|sortable:false|className:text-left'),
                //         $this->makeColumn('name:created_at|label:Dipebarui|sortable:false|className:text-center'),
                //     ]
                // ],
                
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No'),
                        $this->makeColumn('name:module|label:Menu|className:text-left'),
                        $this->makeColumn('name:message|label:Deskripsi|className:text-left'),
                        $this->makeColumn('name:created_by|label:Diperbarui'),
                    ],
                ],
            ]
        );
        return $this->render($this->view . '.index');
    }

    public function getNotificationComponent(Request $request)
    {
        if (auth()->user()) {
            $unreadCount = auth()->user()->unreadNotifications()->count();
            $notificationComponent = View::make('layouts.partials.notification', ['unreadCount' => $unreadCount])->render();
        } else {
            $notificationComponent = '';
        }

        return response()->make($notificationComponent);
    }
}
