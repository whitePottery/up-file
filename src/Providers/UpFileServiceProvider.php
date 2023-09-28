<?php

namespace UpFile\Providers;

use Illuminate\Support\ServiceProvider;

class UpFileServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $pathPackege = base_path('vendor/whitepottery/up-file/src/');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \UpFile\Console\Commands\ExampleCommand::class,
            ]);
        }

        $this->loadViewComponentsAs('upfile', [
            // \UpFile\View\Components\UpImg::class,
            // \UpFile\View\Components\Cut::class,
            \UpFile\View\Components\PrintImg::class,
            \UpFile\View\Components\CutImg::class,
            // Button::class,
        ]);

        $this->loadViewsFrom($pathPackege . 'resources/views', 'up-file');

        $this->loadRoutesFrom(__DIR__ . '/../routes/up-file.php');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'upfile');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishFiles($pathPackege);
    }

    private function publishFiles($pathPackege)
    {
        $copyList = $this->getListPublish();

        foreach ($copyList as $key => $pathTo) {

            $pathFrom = $pathPackege . 'copy/' . $key;

            if (file_exists($pathFrom)) {
                $this->publishes([
                    $pathFrom => $pathTo,
                ], 'upfile');
            }

        }

    }

    private function getListPublish()
    {
        return [
            // 'migrations'  => database_path('migrations'),
            // 'Controllers' => app_path('Http/Controllers/UpFile'),
            'views' => resource_path('views'),
            'js'    => public_path('up-file/js'),
            'css'   => public_path('up-file/css'),
            'image' => public_path('up-file/image'),
        ];
    }

}
