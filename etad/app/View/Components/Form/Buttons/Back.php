<?php

namespace App\View\Components\Form\Buttons;

use Illuminate\View\Component;

class Back extends Component
{
    public $url;
    public $class;
    public $label;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($url, $class = '', $label = 'Kembali')
    {
        $this->url = $url;
        $this->class = $class;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.buttons.back');
    }
}
