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

        $copyList = [

            'migrations'  => database_path('migrations'),
            'Controllers' => app_path('Http/Controllers/UpFile'),
            'views'       => resource_path('views/up-file'),
            'js'          => public_path('js/up-file'),
        ];

        $pathPackege = base_path('vendor/whitepottery/up-file/src/');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \UpFile\Console\Commands\ExampleCommand::class,
            ]);
        }

        $this->loadViewComponentsAs('upfile', [
            \UpFile\View\Components\UpImg::class,
            \UpFile\View\Components\Cut::class,
            \UpFile\View\Components\PrintImg::class,
            \UpFile\View\Components\CutImg::class,
            // Button::class,
        ]);

        $this->loadViewsFrom($pathPackege . 'resources/views', 'up-file');

        $this->loadRoutesFrom(__DIR__ . '/../routes/up-file.php');

        foreach ($copyList as $key => $pathTo) {

            $pathFrom = $pathPackege . 'copy/' . $key;

            if (file_exists($pathFrom)) {
                $this->publishes([
                    $pathFrom => $pathTo,
                ], 'public');
            }

        }

        /*
    $this->publishes([
    __DIR__ . '/../copy/Controllers/UpFile' => app_path('Http/Controllers'),
    ], 'public');
     */

    }

}
