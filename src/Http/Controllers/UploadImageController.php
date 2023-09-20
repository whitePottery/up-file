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
    public static $path_cut = 'public/images/cut';

    public function store(Request $request)
    {
// return response()->json($request);
        $data = json_decode($request->data);

        $upImg = new UploadImage;
        //записываем изображение в хранилище
        $fileImg = $request->file('image')->store(self::$path_img);
        // меняем размер изображения
        $pathImg = $this->resizeImg($data->property, $fileImg);

        // $name_img = pathinfo($pathImg, PATHINFO_FILENAME);
        // $ext_img  = pathinfo($pathImg, PATHINFO_EXTENSION);
        //получаем урл без файла
        $upImg->src = Storage::url($pathImg);
        //полный урл с файлом

        foreach ($data->table as $key => $value) {
            $upImg->$key = $value;
        }

        $res = $upImg->save();

        $responce = ['image' => $this->cardCreate($upImg)];

        return response()->json($responce);
    }

    public function getImage(UploadImage $upImg, $nameImg, $user_id, $postId = 0)
    {

        $images = $upImg->select('id', 'url', 'post_id', 'user_id')->where('name_img', $nameImg)->where('post_id', $postId)
        ->when(0 == $postId,function ($query) use( $user_id ) {
            $query->where('user_id', $user_id);
        })
        ->get();

        $responce = ['images' => $images];

        return response()->json($responce);
    }

    /*

     */
    public function delete(Request $request)
    {

        $image = UploadImage::find($request->id);
        $nameImg = basename($image->src);

        $msq    = Storage::delete(self::$path_img . '/' . $nameImg);
        $msq    = Storage::delete(self::$path_cut . '/' . $nameImg);

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

        $data = json_decode($request->data);

        $newFile = self::$path_cut . '/' . basename($data->property->url);

        $src_cut = MyImageService::cropStorage($data->property->image, $newFile);

        UploadImage::where('id', $id)->update(['src_cut' => $src_cut]);

        return response()->json($src_cut);
    }

/*

 */
    public function imageAlt(Request $request)
    {
        $data = $request->validate([
            'alt' => 'required|string|max:300',
        ]);

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
        $path_cut = $path . '/cut/' . $nameImg;

        $image = ImageTools::make(Storage::path($fileImg));

        Storage::delete($fileImg);

        if ('100%' != $property->heightImg && '100%' != $property->widthImg) {

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
