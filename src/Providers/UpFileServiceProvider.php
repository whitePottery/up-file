<?php

namespace UpFile\Providers;

use App\Console\Commands\CreatePackage;
use Illuminate\Support\ServiceProvider;

class UpFileServiceProvider extends ServiceProvider
{

    private $copyList = [

        'migrations'  => 'migrations',
        'Controllers' => 'Http/Controllers/UpFile',
        'views'       => 'views/up-file',
        'js'          => 'js/up-file',
    ];



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

        $this->loadViewsFrom($pathPackege . 'resources/views', 'up-file');

        //$this->loadRoutesFrom(__DIR__.'/../routes/up-file.php');

        foreach ($this->copyList as $key => $pathTo) {

            $pathFrom = $pathPackege.'copy/' . $key;

            if (file_exists($pathFrom)) {
                $this->publishes([
                    $pathFrom => database_path($pathTo),
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
