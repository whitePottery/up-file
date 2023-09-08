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


<x-upfile-cut name="image" :src="Storage::url($news->image)"/>
<x-upfile-up-img name="image-blog" :post-id="$news->id"/>




UpFile\Models\ImageModel;
  class News extends ImageModel

    use HasFactory;

копировать миграции картинку

вставить @stack('css') и @stack('js_scripts')


скопировать миграции
скопировать файл отсутствия картинки
скопировать себе файлы query croppie css js в папки


<x-upfile-print-img name="image-news" :post-id="$news->id" class="n-slider"/>





