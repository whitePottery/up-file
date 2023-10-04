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
php artisan vendor:publish --tag=upfile
```

Выполнить миграцию
 ```
    php artisan migrate
 ```

В Html.blade файле добавить соответственно в низу стилей @stack('styles')
и внизу под скриптами @stack('scripts')

в модели которая будет использовать для обработки постов с изображениями
сделать расширение от  ImageModel

Пример:
```
use UpFile\Models\ImageModel;

class Blog extends ImageModel
{

}

```

В файле в форму в котором будут загружаться изображения вставить в нужном месте компонент.
Например:
страница админки  blog create

```
<x-upfile-cut-img class="" name="mini-blog" width="500" height="300" max-height="700"/>
```
страница админки  blog edit
```
<x-upfile-cut-img class="" :post-id="$blog->id" name="mini-blog" width="500" height="300"/>
```

В файле blade.php в котором будут выводиться изображения нужно вставить компонент
например home blog
```

<x-upfile-print-img name="image-blog" :post-id="$blog->id" class="slider"/>
```
Файл для редактирования вывода изображений находится в папке:
resources/views/vendor/print-img/print-img.blade.php

Атрибуты компонента <x-upfile-cut-img/>

name - название тега+название модели пишется через тире(name-model)

Атрибуты компонента <x-upfile-print-img/>


Если не создана ссылка на папку storage введите команду:
```

php artisan storage:link
```


## удаление пакета

```
composer remove whitePottery/up-file
```


<!--
вынести css в отдельный файл
переделать модальное окно без батстрапа
отказаться от croppie(написать самому)
переписать все на чистом js


для переопределения слов перевода
создать нужные файлы в папке /resources/lang/vendor/upfile/en/image.php( английский вариант )
upfile - тег определенный в провайдере vendor/whitepottery/up-file/src/Providers/UpFileServiceProvider.php( строка $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'upfile');)
-->