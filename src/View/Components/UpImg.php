<?php

namespace UpFile\View\Components;

use Illuminate\View\Component;
use UpFile\Models\UploadImage;

class UpImg extends Component
{

    public $images;

    public $name;

    public $postId;

    public $user_id;
    // public $modelImg;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name = 'upImage', $postId = 0) //, $modelImg = false)
    {

        $this->name = $name;

        $this->postId = $postId;

        $this->user_id = auth()->id();

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

        $data = UploadImage::select('id', 'url', 'post_id', 'user_id')->where('type_page', $this->name)->where('post_id', $this->postId)->where('user_id', $this->user_id)->get();

        $data->tmpStyle = 'style = \"opacity:0.5\"';

        $this->images = \View::make('up-file::components.up-img.up-img-card', ['images'=>$data]);
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
