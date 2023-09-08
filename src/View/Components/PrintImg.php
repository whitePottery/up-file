<?php

namespace UpFile\View\Components;

use Illuminate\View\Component;
use UpFile\Models\UploadImage;

class PrintImg extends Component
{

    public $class;
    public $images;
    public $postId;
    public $nameImg;

    // public $modelImg;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $postId, $class = '') //, $modelImg = false)

    {
        $this->nameImg = explode('-', $name)[0];

        $this->nameModel = explode('-', $name)[1] ?? die('в компоненте - "x-upfile-up-img" атрибут name должен иметь вид "name-model" '); //str_replace('.','_',\Request::route()->getName());

        $this->postId = $postId;

        $this->class = $class;

        $this->cardCreate();

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // return view('up-file::components.print-img.print-img');
        return view('vendor.print-img.print-img');
    }

    private function cardCreate()
    {

        $this->images = UploadImage::select('id', 'url', 'alt')->where('name_img', $this->nameImg)->where('name_model', $this->nameModel)->where('post_id', $this->postId)->get();

    }

}
