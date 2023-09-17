<?php
namespace UpFile\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use UpFile\Models\UploadImage;
use UpFile\Services\ImageResize;
use UpFile\Services\MyImageService;

class UploadImageController extends Controller
{

    public static $path_img = 'public/images';
    public static $path_mini= 'cut';

    public function store(Request $request)
    {

// return response()->json($request);
        // $validatedData = $request->validate([
        //     'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

        $data = json_decode($request->data);

        $upImg = new UploadImage;
// return response()->json($data->property);
        // if(isset($data->property->image))
        //    $upImg->path =  MyImageService::cropProperty($data->property, 'public/images');
        // else{
        // $path_img='public/images';
        //записываем изображение в хранилище
        $fileImg         = $request->file('image')->store(self::$path_img);
        $upImg->name_img = basename($fileImg);
// return response()->json($upImg->path_img);
        // меняем размер изображения
        $this->resizeImg($data->property, $fileImg);
        // }
        //получаем урл изображения
        $upImg->url_img = Storage::url(self::$path_img);

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
        $msq = Storage::delete(self::$path_img . '/' . $image->name_img);
        $msq = Storage::delete(self::$path_img . '/' .self::$path_mini. '/' .$image->name_img);
        $images = $image->delete();

        $responce = ['message' => 'Image deleted'];

        return response()->json($responce);
    }

    /**
     * [saveCutFile description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function saveCutFile(Request $request, $id)
    {


        $path = self::$path_img .'/'. self::$path_mini;

        $data = json_decode($request->data);

        $newFile = $path . '/' .pathinfo($data->property->url, PATHINFO_FILENAME);

        $nameExt = pathinfo($data->property->url, PATHINFO_EXTENSION);
        // $newFile = $path . '/' .basename($data->property->url);



        $file = MyImageService::cropStorage($data->property->image, $path);
        return response()->json($id);
        if(Storage::exists($newFile)) {
            // return $newFile;
// return response()->json($newFile);
            Storage::delete($newFile);}

        Storage::move($file, $newFile);

        UploadImage::where('id',$id)->update(['path_mini'=>self::$path_mini]);
        // $image->save();

        return response()->json(Storage::url($newFile));
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
        return (string) \View::make('up-file::components.cut-img.cut-img-card', ['images' => [$image]]);
    }
    /**
     * [resizeImg description]
     * @param  [type] $property [description]
     * @param  [type] $path     [description]
     * @return [type]           [description]
     */
    public function resizeImg($property, $path)
    {

        $image = ImageResize::make(Storage::path($path));

        if ($property->heightImg > 0) {

            $image->resizeToHeight($property->heightImg);

        } elseif ($property->widthImg > 0) {

            $image->resizeToWidth($property->widthImg);
        }

        $image->save(Storage::path($path));

    }

//     /*
// --------------------------------------------------------------
//  Изменение размера изображения
// --------------------------------------------------------------
//  */function img_resize ($file_in,$file_out,$h_size,$w_size=0){
//     $image = new img_resize;
// //echo $file_in;
//     $image->load(Storage::path($path));
//     if(0!=$w_size)
//       $image->resize($w_size,$h_size);
//     elseif('w'==$w_size)
//       $image->resizeToWidth($h_size);
//     else
//       $image->resizeToHeight($h_size);
//     $image->save($file_out,$this->extype);

// return true;
// }

    // public function resizeImg($property, $path)
    // {

    //     $img = Image::make(Storage::path($path));

    //     if ($property->heightImg > 0) {
    //         $img->resize(null, $property->heightImg, function ($constraint) {
    //             $constraint->aspectRatio();
    //         });
    //     } elseif ($property->widthImg > 0) {
    //         $img->resize($property->widthImg, null, function ($constraint) {
    //             $constraint->aspectRatio();
    //         });
    //     }

    //     $img->save(Storage::path($path));

    // }

}
