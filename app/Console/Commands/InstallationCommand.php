<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstallationCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'service:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install service.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:cache');
        \Artisan::call('migrate', ["--force"=> true ]);
        \Artisan::call('db:seed', ["--force"=> true ]);
        \Artisan::call('jwt:generate');
    }
}
