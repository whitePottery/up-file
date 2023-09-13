<?php

namespace UpFile\View\Components;

use Illuminate\View\Component;
use UpFile\Models\UploadImage;

class UpImg extends Component
{

    public $images;

    public $nameImg;

    public $nameModel;

    public $postId;

    public $user_id;

    public $widthImg;
    public $heightImg;

    public $widthCut;
    public $heightCut;

    // public $modelImg;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $postId = 0, $width = 0, $height = 0, $maxWidth = 0, $maxHeight = 0) //, $modelImg = false)

    {

        $this->nameImg = explode('-', $name)[0];

        $this->nameModel = explode('-', $name)[1] ?? die('в компоненте - "x-upfile-up-img" атрибут name должен иметь вид "name-model" '); //str_replace('.','_',\Request::route()->getName());

        $this->postId = $postId;

        $this->user_id = auth()->id();

        $this->widthImg=$maxWidth;
        $this->heightImg=$maxHeight;

        $this->widthCut=$width;
        $this->heightCut=$height;

        $this->cardCreate();

        // $this->modelImg = $modelImg?json_encode($modelImg):0;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('up-file::components.up-img.up-img');

    }

    private function cardCreate()
    {

        $data = UploadImage::select('id', 'url', 'post_id', 'user_id')->where('name_img', $this->nameImg)->where('name_model', $this->nameModel)->where('post_id', $this->postId)->where('user_id', $this->user_id)->get();

        // $data->tmpStyle = 'style = \"opacity:0.5\"';
        // dd($this->postId);
        $this->images = \View::make('up-file::components.up-img.up-img-card', ['images' => $data]);
    }

}

// php artisan make:model Image -mcr

//     $table->id();
//     $table->integer('user_id')->unsigned();
//     $table->integer('post_id')->unsigned();
//     $table->string('type_page');
//     $table->string('image');
//     $table->string('url');
//     $table->string('alt')->nullable();
//     $table->timestamps();
