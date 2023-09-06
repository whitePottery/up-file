<?php

namespace UpFile\View\Components;

use Illuminate\View\Component;

class Cut extends Component
{


        public $name;
        public $src;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name="cut", $src='')
    {
        $this->name = $name;
        $this->src = $src;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('up-file::components.cut.cut');
    }
}
