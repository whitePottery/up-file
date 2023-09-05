<?php

namespace UpFile\Models;

use UpFile\Models\UploadImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageModel extends Model
{
    use HasFactory;

    public $typePage = 0;

    /**
     * [boot description]
     * @return [type] [description]
     */
    public static function boot()
    {
        parent::boot();
        // Автоматически добавляем id пользователя к модели перед записью в базу
        static::creating(function ($model) {
            $model->user_id = auth()->user()->id;

        });

        static::created(function ($model) {

            $model->updateImage();

        });

        static::updating(function ($model) {
            $model->user_id = auth()->user()->id;

        });

        static::deleting(function ($model) {

            $images = $model->destroyImages();

            $images[] = $model->image;

            \Illuminate\Support\Facades\Storage::delete($images);
        });

    }

    /**
     * [destroyImages description]
     * @return [type] [description]
     */
    public function destroyImages()
    {

        $images = UploadImage::where('post_id', $this->id)->where('type_page', $this->typePage)->pluck('image', 'id')->toArray();

        UploadImage::destroy(array_keys($images));

        return $images;
    }

    /**
     * [getImages description]
     * @return [type] [description]
     */
    public function getImages($limit=100)
    {
        return UploadImage::where('post_id', $this->id)->where('type_page', $this->typePage)->offset(0)->limit($limit)->get();
    }

    /**
     * [updateImage description]
     * @return [type] [description]
     */
    public function updateImage()
    {
        return UploadImage::where('post_id', 0)->where('type_page', $this->typePage)->update(['post_id' => $this->id]);
    }

}
