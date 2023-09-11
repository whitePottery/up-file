<p align="center">
<img src="info/logo.jpg">
</p>


## Установка из composer

```
composer require whitePottery/up-file
```

 Опубликовать js файлы, вью и миграции необходимые для работы пакета.
Вызывать команду:
```
php artisan vendor:publish --provider="Pottery\Providers\PotteryServiceProvider"
```

Выполнить миграцию
 ```
    php artisan migrate
 ```

<x-upfile-cut name="image" width="300" height="100"/>
<x-upfile-cut name="image" :src="Storage::url($news->image)" width="300" height="100"/>


<x-upfile-up-img name="image-blog" :post-id="$news->id"/>



UpFile\Models\ImageModel;
  class News extends ImageModel

    use HasFactory;

копировать миграции картинку

вставить @stack('styles') и @stack('js_scripts')


скопировать миграции
скопировать файл отсутствия картинки
скопировать себе файлы query croppie css js в папки


<x-upfile-print-img name="image-news" :post-id="$news->id" class="n-slider"/>



передать данные ширины высоты cropic

width="500" height="100"

высота картинки


class Image заменить своим