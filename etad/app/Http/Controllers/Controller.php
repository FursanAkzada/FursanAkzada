<?php

namespace App\Http\Controllers;

use App\Entities\Traits\ResponseTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ResponseTrait;

    private $attr = [
        'title'       => "Title",
        'subtitle'    => "This is Subtitle",
        'breadcrumb'  => ["Home" => "#"],
        'module'      => "",
        'route'       => "",
        'view'        => "",
        'tableStruct' => [],
    ];

    public function setPrepare($binding)
    {
        $this->attr = array_merge($this->attr, $binding);
        if ($this->attr['view'] == '') {
            $this->attr['view'] = $this->attr['route'];
        }
        if ($this->attr['module'] == '') {
            $this->attr['module'] = str_replace('.', '_', $this->attr['route']);
        }
        $this->attr['title'] = __($this->attr['title']);
        request()->merge([
            'module' => $this->getPrepare('module'),
            'route' => $this->getPrepare('route'),
        ]);
    }

    public function getPrepare($name = null)
    {
        if ($name) {
            return $this->attr[$name];
        }
        return $this->attr;
    }

    /* Modals */
    public function setModalSize($value = "#medium")
    {
        $this->attr['modalSize'] = $value;
    }
    public function getModalSize()
    {
        return $this->attr['modalSize'];
    }

    /* Breadcrumb */
    public function setBreadcrumb($value = [])
    {
        $this->attr['breadcrumb'] = $value;
    }
    public function pushBreadCrumb($value = [])
    {
        $this->attr['breadcrumb'] = array_merge($this->attr['breadcrumb'], $value);
    }
    public function getBreadcrumb()
    {
        return $this->attr['breadcrumb'];
    }

    /* Table Struct */
    public function setTableStruct($value = [])
    {
        $this->attr['tableStruct'] = $value;
    }
    public function getTableStruct()
    {
        return $this->attr['tableStruct'];
    }

    /* Routes */
    public function setRoutes($value)
    {
        $this->attr['route'] = $value;
    }
    public function getRoutes()
    {
        return $this->attr['route'];
    }

    /* Datatables */
    public function datatables()
    {
        return response()->json([
            'grid' => Route::has($this->attr['route'] . '.grid') ? route($this->attr['route'] . '.grid', request()->all()) : '',
            'column' => json_encode($this->attr['tableStruct'], true),
        ]);
    }

    /* Title */
    public function setTitle($value = "")
    {
        $this->attr['title'] = $value;
    }
    public function getTitle()
    {
        return $this->attr['title'];
    }

    /* Subtitle */
    public function setSubtitle($value = "")
    {
        $this->attr['subtitle'] = $value;
    }
    public function getSubtitle()
    {
        return $this->attr['subtitle'];
    }

    /* Perms */
    public function setPerms($value = "")
    {
        $this->perms = $value;
    }
    public function getPerms()
    {
        return $this->perms;
    }

    /* Render */
    public function render($view, $binding = [])
    {
        $user = auth()->user();

        if ($user->status === 'nonactive') {
            return view('nonactive');
        }
        $this->setPrepare($binding);
        if (empty($this->attr['perms']) || $user->checkPerms($this->attr['perms']) || $user->checkPerms($this->attr['perms'] . '.view')) {
            return view($view, $this->attr);
        } else {
            return view('errors.403', $this->attr);
        }
    }

    /* Make Button */
    public function makeButton($params = [])
    {
        $settings = [
            'id'       => '',
            'class'    => '',
            'label'    => 'Button',
            'tooltip'  => '',
            'url'      => '',
        ];

        $btn   = '';
        $datas = '';
        $attrs = '';

        if (isset($params['datas'])) {
            foreach ($params['datas'] as $k => $v) {
                $datas .= ' data-' . $k . '="' . $v . '"';
            }
        }

        if (isset($params['attributes'])) {
            foreach ($params['attributes'] as $k => $v) {
                $attrs .= ' ' . $k . '="' . $v . '"';
            }
        }

        if (isset($params['type'])) {
            switch ($params['type']) {
                case 'modal':
                    $settings['class']   = 'base-modal--render text-primary';
                    break;

                case 'show':
                    $settings['class']   = 'base-modal--render text-primary';
                    $settings['label']   = '<i class="mx-2 fa fa-eye"></i>';
                    $settings['tooltip'] = 'Preview';
                    $settings['url']     = $params['url'] ?? route($this->route . '.show', $params['id']);
                    break;

                case 'page-show':
                    $settings['class']   = 'base-content--replace text-primary';
                    $settings['label']   = '<i class="mx-2 fa fa-eye"></i>';
                    $settings['tooltip'] = 'Preview';
                    $settings['url']     = $params['url'] ?? route($this->route . '.show', $params['id']);
                    break;

                case 'edit':
                    $settings['class']   = 'base-modal--render text-success';
                    $settings['label']   = '<i class="mx-2 fa fa-edit"></i>';
                    $settings['tooltip'] = 'Ubah';
                    $settings['url']     = $params['url'] ?? route($this->route . '.edit', $params['id']);
                    break;

                case 'page-edit':
                    $settings['class']   = 'base-content--replace text-success';
                    $settings['label']   = '<i class="mx-2 fa fa-edit"></i>';
                    $settings['tooltip'] = 'Ubah';
                    $settings['url']     = $params['url'] ?? route($this->route . '.edit', $params['id']);
                    break;

                case 'delete':
                    $settings['class']   = 'base-modal--confirm text-danger';
                    $settings['label']   = '<i class="mx-2 fa fa-trash"></i>';
                    $settings['tooltip'] = 'Hapus';
                    $settings['url']     = $params['url'] ?? route($this->route . '.destroy', $params['id']);
                    if (isset($params['confirm_text'])) {
                        $datas .= 'data-confirm-text="' . $params['confirm-text'] . '"';
                    } else {
                        $datas .= 'data-confirm-text="Are you sure?"';
                    }
                    break;

                case 'upgrade':
                    $settings['class']   = 'base-modal--postByUrl text-warning';
                    $settings['label']   = '<i class="mx-2 fa fa-sync text-warning"></i>';
                    $settings['tooltip'] = 'Upgrade Versi';
                    $settings['url']     = $params['url'] ?? route($this->route . '.edit', $params['id']);
                    break;

                case 'reset-password':
                    $settings['class']   = 'base-modal--postByUrl text-warning';
                    $settings['label']   = '<i class="mx-2 fa fa-sync text-warning"></i>';
                    $settings['tooltip'] = 'Reset password akan mengubah password menjadi: qwerty123456';
                    $settings['url']     = $params['url'] ?? route($this->route . '.resetPassword', $params['id']);
                    if (isset($params['confirm_text'])) {
                        $datas .= 'data-confirm-text="' . $params['confirm-text'] . '"';
                    } else {
                        $datas .= 'data-confirm-text="Are you sure?"';
                    }
                    break;

                case 'activate':
                    $settings['class'] = 'base-form--activate btn btn-sm';
                    $settings['url']   = $params['url'] ?? route($this->route . '.activate', $params['id']);
                    $datas .= 'data-status="' . $params['status'] . '"';
                    if ($params['status'] === 1) {
                        $settings['class']   .= ' btn-primary';
                        $settings['label']   = 'ACTIVE';
                        $settings['tooltip'] = 'Status is active';
                    } else {
                        $settings['class']   .= ' btn-default';
                        $settings['label']   = 'NONACTIVE';
                        $settings['tooltip'] = 'Status is nonactive';
                    }
                    break;

                case 'page':
                case 'url':
                default:
                    $settings['class']   = 'base-content--replace';
                    $settings['label']   = '<i class="fa fa-eye"></i>';
                    break;
            }
        }

        $params  = array_merge($settings, $params);
        $btn = '<a href="' . $params['url'] . '"
                    class="' . $params['class'] . '"
                    data-toggle="tooltip"
                    data-container="#content"
                    title="' . $params['tooltip'] . '"
                    ' . $datas . ' ' . $attrs . ' ' . '>
                    ' . $params['label'] . '
                </a>';

        return $btn;
    }


    /* Make Column */
    public function makeColumn($params)
    {
        $columns = [
            'name' => '',
            'data' => '',
            'label' => '',
            'width' => '',
            'sortable' => false,
            'className' => '',
        ];
        if (is_string($params)) {
            $params = str_to_array($params, '|', ':');
        }
        $params['data']      = $params['data'] ?? $params['name'];
        $params['label']     = $params['label'] ?? ucfirst(str_replace('_', ' ', $params['name']));
        $params['className'] = $params['className'] ?? 'text-center';
        $params = array_merge($columns, $params);
        $params['label'] = __($params['label']);
        return $params;
    }

    /* Make Label */
    public function makeLabel($label, $color = 'primary', $params = [])
    {
        $default = [
            'class' => '',
            'style' => '',
        ];
        $params = array_merge($default, $params);
        return '<span class="label label-' . $color . ' label-inline ' . $params['class'] . '" style="' . $params['style'] . '">' . $label . '</span>';
    }

    /* Make Dropdown */
    public function makeDropdown($params = [])
    {
        $btn = '';
        if (!empty($params)) {
            $btn = '<div class="dropdown dropright">
                        <a class="btn btn-light btn-icon d-inline-block btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu">';
            foreach ($params as $item) {
                if (is_string($item)) {
                    $item = str_to_array($item, '|', ':');
                }
                // dd($item['url']);
                $default = [
                    'modal' => true,
                    'class' => (!empty($item['page'])) ? '' : 'base-modal--render',
                    'url' => 'javascript:;',
                    'icon' => '',
                    'label' => 'Title',
                    'attrs' => '',
                    'hidden' => false,
                ];
                if (!empty($item['type'])) {
                    switch ($item['type']) {
                        case 'create':
                            $default['icon'] = 'far fa-plus text-primary fa-fw';
                            $default['label'] = 'Create';
                            $default['url'] = '';
                            break;
                        case 'show':
                            $default['icon'] = 'far fa-eye text-primary fa-fw';
                            $default['label'] = 'Lihat';
                            $default['url'] = route($this->getPrepare('route') . '.show', $item['id']);
                            break;
                        case 'approval':
                            $default['icon'] = 'far fa-check-circle text-primary fa-fw';
                            $default['label'] = 'Approval';
                            $default['url'] = route($this->getPrepare('route') . '.approval', $item['id']);
                            break;
                        case 'edit':
                            $default['icon'] = 'far fa-edit text-warning fa-fw';
                            $default['label'] = 'Ubah';
                            $default['url'] = route($this->getPrepare('route') . '.edit', $item['id']);
                            break;
                        case 'print':
                            $default['icon'] = 'fa fa-print text-dark fa-fw';
                            $default['label'] = 'Cetak';
                            $default['url'] = route($this->getPrepare('route') . '.print', $item['id']);
                            $default['class'] = '';
                            $default['attrs'] = 'target="_blank"';
                            break;
                        case 'download':
                            $default['icon'] = 'fa fa-download text-dark fa-fw';
                            $default['label'] = 'Download';
                            $default['url'] = route($this->getPrepare('route') . '.download', $item['id']);
                            $default['class'] = '';
                            $default['attrs'] = 'target="_blank"';
                            break;
                        case 'delete':
                            $default['icon'] = 'fa fa-trash text-danger fa-fw';
                            $default['label'] = 'Hapus';
                            $default['class'] = 'base-modal--delete';
                            $default['url'] = route($this->getPrepare('route') . '.destroy', $item['id']);
                            break;
                        case 'upgrade':
                            $default['icon'] = 'fa fa-sync text-warning fa-fw';
                            $default['label'] = 'Upgrade Version';
                            $default['class'] = 'base-modal--render';
                            $default['url'] = route($this->getPrepare('route') . '.getUpgrade', $item['id']);
                            break;
                        case 'confirm':
                            $default['icon'] = 'fa fa-check text-success fa-fw';
                            $default['label'] = 'Confirm';
                            $default['class'] = 'base-modal--confirm';
                            $default['url'] = '';
                            break;
                        case 'custom':
                            $default['icon'] = 'far fa-plus text-primary fa-fw';
                            $default['label'] = 'Custom';
                            $default['url'] = '';
                            break;
                    }
                    $item = array_merge($default, $item);
                    if (!$item['hidden']) {
                        if ($item['type'] === 'divider') {
                            $btn .=  "<div class=\"dropdown-divider\"></div>";
                        } else {
                            $tooltip = empty($item['tooltip']) ? $item['label'] : $item['tooltip'];
                            $btn .= '<a class="dropdown-item ' . $item['class'] . '" href="' . $item['url'] . '" data-toggle="tooltip" title="' . $tooltip . '" ' . $item['attrs'] . '>
                                <i class="' . $item['icon'] . ' fa-fw mr-2"></i>' . $item['label'] . '
                            </a>';
                        }
                    }
                }
            }
            $btn .= '</div>
                    </div>';
        }
        return $btn;
    }

    public function saveActivity($params)
    {
        $activity = new \Modules\Settings\Entities\Activity;
        $activity->saveTrail($params);
    }
}
