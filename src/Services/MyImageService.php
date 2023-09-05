<?php

namespace UpFile\Services;

use Illuminate\Support\Facades\Storage;

class MyImageService extends ImageResize
{

    /**
     * [saveBase64 description]
     *
     * @param  array  $image [description]
     * @param  [type] $path  [description]
     * @return [type]        [description]
     */
    public function saveBase64($image, $path)
    {
                // dd($path);
        $image_parts    = explode(";base64,", $image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type     = $image_type_aux[1];
        $image_base64   = base64_decode($image_parts[1]);
        $file           = $path .'/'. uniqid() . '. ' . $image_type;
        Storage::put($file, $image_base64);

        return $file;
    }

    /**
     * [cropStorage description]
     *
     * @param  array $data [description]
     * @param  string: $path [description]
     * @return string: $image :path file
     */
    public function cropStorage($data, $path)
    {
        ! empty($data['image_base64'])
        ? $image = $this->saveBase64($data['image_base64'], $path)
        : $image = Storage::put($path, $data['image']);

        return $image;
    }



}
