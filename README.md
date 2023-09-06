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
<x-upfile-up-img name="image" :post-id="$news->id"/>



  class News extends ImageModel
{
    use HasFactory;


    const TYPE_PAGE = 1;
    public $name = ['image11','image'];



копировать миграции картинку