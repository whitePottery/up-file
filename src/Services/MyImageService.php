<?php

namespace UpFile\Services;

use Illuminate\Support\Facades\Storage;
use UpFile\Services\ImageTools;

class MyImageService
{
    /**
     * [saveBase64 description]
     *
     * @param  array  $image [description]
     * @param  [type] $path  [description]
     * @return [type]        [description]
     */
    public static function saveBase64($image, $path)
    {
        // dd($path);
        $image_parts    = explode(";base64,", $image);
        $image_type_aux = explode("image/", $image_parts[0]);
        // dd($image_type_aux);
        $image_type   = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        // $file         = $path . '/' . uniqid() . '.' . $image_type;
        Storage::put($path, $image_base64);

        return $path;
    }

    /**
     * [cropStorage description]
     *
     * @param  array $image [description]
     * @param  string: $path [description]
     * @return string: $image :path file
     */
    public static function cropStorage($image, $path)
    {

        return self::saveBase64($image, $path);


    }

        public function moveImgCut($file, $newFile)
    {



        // $extFile = pathinfo($data->property->url, PATHINFO_EXTENSION);

        // if (Storage::exists($newFile)) {
        //     // return response()->json($newFile);
        //     Storage::delete($newFile);
        // }

        $image = ImageTools::make(Storage::path($file));
        Storage::delete($file);
// return $extFile;
        $image->save(Storage::path($newFile));

        // Storage::move($file, $newFile);
    }

     public static function cropProperty($property, $path)
    {

        // return self::saveBase64($image, $path);

    }

}
