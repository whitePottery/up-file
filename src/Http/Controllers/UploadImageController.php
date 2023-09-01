<?php
namespace App\Http\Controllers;

use App\Models\UploadImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadImageController extends Controller
{
    public function store(Request $request, $typePage, $postId = 0)
    {

        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

        ]);

        $upImg = new UploadImage;

        // $upImg->name = $request->file('image')->getClientOriginalName();

        $upImg->image = $request->file('image')->store('public/images');

        //Создаем миниатюру изображения и сохраняем ее
        $img = Image::make(Storage::path($upImg->image));
        $img->resize(null, 600, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save(Storage::path($upImg->image));


        $upImg->url = Storage::url($upImg->image);

        $upImg->user_id = auth()->user()->id;

        $upImg->type_page = $typePage;

        $upImg->post_id = $postId;

        $upImg->save();

        // $responce = ["error" => 'Error'];

        $responce = ["url" => $upImg->url, "id" => $upImg->id];

        return response()->json($responce);

    }

    public function getImage(UploadImage $upImg, $typePage, $postId = 0)
    {

        $images = $upImg->select('id', 'url','post_id')->where('type_page', $typePage)->where('post_id', $postId)->get();

        $responce = ['images' => $images];

        return response()->json($responce);
    }

    /*

    */
    public function delete(Request $request)
    {

        // $modelImage = $this->modelCheck($request);
// return ($modelImage);
        $image = UploadImage::find($request->id);
// return($image);
        $msq = Storage::delete($image->image);

        $images = $image->delete();

        $responce = ['message' => 'Image deleted'];

        return response()->json($responce);
    }

/*

 */
    public function imageAlt(Request $request, UploadImage $image)
    {

        // $modelImage = $this->modelCheck($request);

        // $img = $modelImage->where('id', $request->id)->first();

        $image->alt = $request->alt;

        return $image->save();
    }

    /*

     */
    // public function modelCheck($request)
    // {

    //     $model = $request->model_img
    //     ? new $request->model_img
    //     : new UploadImage;

    //     return $model;
    // }

}
