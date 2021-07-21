<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Dropdown extends Component
{
    public $variable;

    public $name;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($variable, $name)
    {
        $this->variable = $variable;
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.dropdown');
    }
}
