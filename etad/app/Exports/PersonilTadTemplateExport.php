<?php

namespace App\Exports;

use App\Models\Auth\Role;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PersonilTadTemplateExport implements FromView
{
    public function view(): View
    {
        return view('pengajuan::tad.personil.template');
    }
}
