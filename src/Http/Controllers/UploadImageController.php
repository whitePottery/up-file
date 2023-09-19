<?php
namespace UpFile\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use UpFile\Models\UploadImage;
use UpFile\Services\ImageTools;
use UpFile\Services\MyImageService;

class UploadImageController extends Controller
{

    public static $path_img  = 'public/images';
    public static $path_mini = 'cut';

    public function store(Request $request)
    {
// return response()->json($request);
        $data = json_decode($request->data);

        $upImg = new UploadImage;
        //записываем изображение в хранилище
        $fileImg = $request->file('image')->store(self::$path_img);
        // меняем размер изображения
        $pathImg = $this->resizeImg($data->property, $fileImg);

        $upImg->name_img =  pathinfo($pathImg, PATHINFO_FILENAME);
        $upImg->ext_img = pathinfo($pathImg, PATHINFO_EXTENSION);
        //получаем урл без файла
        $upImg->url_img = Storage::url(self::$path_img);
        //полный урл с файлом
        $upImg->url = Storage::url($pathImg);

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
        $msq    = Storage::delete(self::$path_img . '/' . $image->name_img);
        $msq    = Storage::delete(self::$path_img . '/' . self::$path_mini . '/' . $image->name_img);
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

        $path = self::$path_img . '/' . self::$path_mini;

        $data = json_decode($request->data);

        $newFile = $path . '/' . basename($data->property->url);
// return response()->json($newFile);
        // $newFile = $path . '/' .basename($data->property->url);

        $file = MyImageService::cropStorage($data->property->image, $newFile);

        // $newFile = $this->moveImgCut($file, $newFile);
        // return response()->json($newFile);
        UploadImage::where('id', $id)->update(['path_mini' => self::$path_mini]);
        // $image->save();

        return response()->json(Storage::url($newFile));
    }

/*

 */
    public function imageAlt(Request $request)
    {

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
    public function resizeImg($property, $fileImg)
    {

        $path     = pathinfo($fileImg, PATHINFO_DIRNAME);
        $nameImg  = pathinfo($fileImg, PATHINFO_FILENAME) . '.jpg';
        $path_jpg = $path . '/' . $nameImg;

        $image = ImageTools::make(Storage::path($fileImg));

        Storage::delete($fileImg);

        if ('100%' != $property->heightImg && '100%' != $property->widthImg ) {

            if ($property->heightImg > 0) {

                $image->resizeToHeight($property->heightImg);

            } elseif ($property->widthImg > 0) {

                $image->resizeToWidth($property->widthImg);
            }

        }

        $image->save(Storage::path($path_jpg), IMAGETYPE_JPEG);

        return $path_jpg;

    }

}
