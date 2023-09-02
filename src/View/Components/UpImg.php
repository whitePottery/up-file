<?php

namespace UpFile\View\Components;

use Illuminate\View\Component;

class UpImg extends Component
{
    public $typePage;

    public $postId;

        public $user_id;
    // public $modelImg;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($typePage=0, $postId = 0) //, $modelImg = false)

    {
        $this->typePage = $typePage;

        $this->postId = $postId;

        $this->user_id = auth()->id();

        // $this->modelImg = $modelImg?json_encode($modelImg):0;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('up-file::components.up-img');
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
