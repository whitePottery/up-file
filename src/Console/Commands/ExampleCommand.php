<?php

namespace UpFile\Console\Commands;

// use UpFile\Library\UpFileHelper;
// use UpFile\Models\UpFile;
// use UpFile\Models\UpFileSetting;
// use Carbon\Carbon;
use Illuminate\Console\Command;

class ExampleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'up-file:example';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Заготовка команды up-file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {

        $this->info("up-file - Команда выполнена");
    }
}
