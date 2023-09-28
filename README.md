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



UpFile\Models\ImageModel;
  class News extends ImageModel

    use HasFactory;

копировать миграции картинку

resources/views/components/print-img/print-img.blade.php
копировать в папку resources/views/vendor


вставить @stack('styles') и @stack('js_scripts')


скопировать миграции
скопировать файл отсутствия картинки
скопировать себе файлы query croppie css js в папки



функция удаления оригиналов в модели


  <x-upfile-cut-img class="" name="mini-projects" width="500" height="300"/>
  <x-upfile-cut-img class="" :post-id="$project->id" name="mini-projects" width="500" height="300"/>






