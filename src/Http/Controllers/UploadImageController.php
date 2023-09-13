<?php
namespace UpFile\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use UpFile\Models\UploadImage;
use UpFile\Services\MyImageService;

class UploadImageController extends Controller
{

    public function store(Request $request)
    {

// return response()->json($request);
        // $validatedData = $request->validate([
        //     'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

        $data = json_decode($request->data);

        $upImg = new UploadImage;
return response()->json($data->property);
        if(isset($data->property->image))
           $upImg->path =  MyImageService::cropProperty($data->property, 'public/images');
        else{
        //записываем изображение в хранилище
        $upImg->path = $request->file('image')->store('public/images');
        // меняем размер изображения
        $this->resizeImg($data->property, $upImg->path);
        }
        //получаем урл изображения
        $upImg->url = Storage::url($upImg->path);

        foreach ($data->table as $key => $value) {
            $upImg->$key = $value;
        }

        $res = $upImg->save();

        $responce = ['image' => $this->cardCreate($upImg)];

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
        $image->post_id = 0;
        return (string) \View::make('up-file::components.up-img.up-img-card', ['images' => [$image]]);
    }

    public function resizeImg($property, $path)
    {

        $img = Image::make(Storage::path($path));

        if ($property->heightImg > 0) {
            $img->resize(null, $property->heightImg, function ($constraint) {
                $constraint->aspectRatio();
            });
        } elseif ($property->widthImg > 0) {
            $img->resize($property->widthImg, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $img->save(Storage::path($path));

    }

}
