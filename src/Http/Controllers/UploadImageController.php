<?php
namespace UpFile\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use UpFile\Models\UploadImage;

class UploadImageController extends Controller
{

    public function test()
    {
        return __CLASS__;
    }

    public function store(Request $request, $nameImg, $nameModel,$user_id, $postId = 0)
    {
// return response()->json($request->file);
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

        ]);

        $upImg = new UploadImage;

        $upImg->path = $request->file('image')->store('public/images');

        // Создаем миниатюру изображения и сохраняем ее
        $img = Image::make(Storage::path($upImg->path));
        $img->resize(null, 600, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save(Storage::path($upImg->path));
        //записываем изображение в хранилище
        $upImg->url = Storage::url($upImg->path);

        $upImg->user_id = $user_id;

        $upImg->name_img = $nameImg;

        $upImg->post_id = $postId;

        $upImg->name_model = $nameModel;

        $res = $upImg->save();

        $responce = ['image'=>$this->cardCreate($upImg)];;

        return response()->json($responce);
    }

    public function getImage(UploadImage $upImg, $nameImg, $user_id, $postId = 0)
    {

        if (0 == $postId) {
            $images = $upImg->select('id', 'url', 'post_id', 'user_id')->where('name_img', $nameImg)->where('post_id', $postId)->where('user_id', $user_id)->get();
        } else {
            $images = $upImg->select('id', 'url', 'post_id', 'user_id')->where('name_img', $nameImg)->where('post_id', $postId)->get();
        }

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
    public function imageAlt(Request $request)
    {

        // $modelImage = $this->modelCheck($request);

        // $img = $modelImage->where('id', $request->id)->first();
        $image      = UploadImage::find($request->id);
        $image->alt = $request->alt;

        return $image->save();
    }

    private function cardCreate($image)
    {
        $image->post_id=0;
        return (string)\View::make('up-file::components.up-img.up-img-card', ['images' => [$image]]);
    }
}
