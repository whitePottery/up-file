<?php

namespace UpFile\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use UpFile\Models\UploadImage;

class ImageModel extends Model
{
    use HasFactory;

    // public $name = 0;

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
// dd($model->table);
            //
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

        $images = UploadImage::where('post_id', $this->id)->where('name_model', 'LIKE', '%-'.$this->table)->pluck('image', 'id')->toArray();

        UploadImage::destroy(array_keys($images));

        return $images;
    }

    /**
     * [getImages description]
     * @return [type] [description]
     */
    public function getImages($name_model = '', $limit = 100)
    {
        return UploadImage::where('post_id', $this->id)
            ->when($name_model, function ($query, $name_model) {
                return $query->where('name_model', $name_model);
            },
            function ($query) {
                return $query->where('name_model', 'LIKE', '%'.$this->table);
            })
            ->offset(0)->limit($limit)->get();
    }

    /**
     * [getImages description]
     * @return [type] [description]
     */
    public function firstImage()
    {
        return UploadImage::where('post_id', $this->id)->where('name_model', 'LIKE', '%'.$this->table)->first();
    }

    /**
     * [updateImage description]
     * @return [type] [description]
     */
    public function updateImage()
    {
        return UploadImage::where('post_id', 0)->where('name_model', 'LIKE', '%'.$this->table)->update(['post_id' => $this->id]);

    }

}
