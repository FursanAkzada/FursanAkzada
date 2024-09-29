<?php

namespace App\View\Components\Form\Buttons;

use Illuminate\View\Component;

class Draft extends Component
{
    public $via;
    public $class;
    public $label;
    public $label_save;
    public $label_draft;
    public $submit;
    public $confirm;

    public function __construct(
        $via,
        $class = '',
        $label = 'Simpan',
        $label_draft = 'Simpan Sebagai Draft',
        $label_save = 'Simpan & Kirim',
        $submit = 'enabled',
        $confirm = 'true'
    ) {
        $this->via = $via;
        $this->class = $class;
        $this->label = $label;
        $this->label_draft = $label_draft;
        $this->label_save = $label_save;
        $this->submit = $submit;
        $this->confirm = $confirm;
    }

    public function render()
    {
        return view('components.form.buttons.draft');
    }
}
