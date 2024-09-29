<?php

namespace App\View\Components\Form\Buttons;

use Illuminate\View\Component;

class Save extends Component
{
    public $via;
    public $class;
    public $label;
    public $icon;
    public $confirm;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $via,
        $class = '',
        $label = 'Simpan',
        $icon = 'fas fa-save',
        $confirm = 1
    ) {
        $this->via = $via;
        $this->class = $class;
        $this->label = $label;
        $this->icon = $icon;
        $this->confirm = $confirm;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.buttons.save');
    }
}
