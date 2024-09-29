<?php

namespace Modules\Settings\Http\Controllers\Profiles;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $module = 'settings';
    protected $route  = 'settings.profile';
    protected $view   = 'settings::profile';

    public function __construct()
    {
        $this->setPrepare([
            'module' => $this->module,
            'route'  => $this->route,
            'view'  => $this->view,
            'title'  => 'Profil',
            'breadcrumb' => [
                'Profil' => '/'
            ]
        ]);
    }

    public function index()
    {
        if (auth()->user()->isEhc) {
            return $this->render($this->view . '.ehc');
        }
        return $this->render($this->view . '.vendor');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => ''
        ]);
    }

    public function photoIndex()
    {
        return $this->render($this->view . '.photo');
    }

    public function photoStore(Request $request)
    {
        $request->validate(
            [
                'foto' => 'required|file|mimes:png,jpg,jpeg|max:500'
            ]
        );

        return auth()->user()->changePhotoByRequest($request);
    }
}
