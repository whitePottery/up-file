<?php

namespace UpFile\View\Components;

use Illuminate\View\Component;

class CutImg extends Component
{


        public $nameCut;
        public $src;
        public $widthCut;
        public $heightCut;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name="cut", $width= 300, $height=200,$src='')
    {
        $this->nameCut = $name;
        $this->src = $src;
        $this->widthCut = $width;
        $this->heightCut = $height;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('up-file::components.cut-img.cut-img');
    }
}
