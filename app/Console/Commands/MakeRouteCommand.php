<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeRouteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the routes/api.php file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $apiRoutesPath = base_path('routes/api.php');
        if (!file_exists($apiRoutesPath)) {
            touch($apiRoutesPath);
            $this->info('routes/api.php file created successfully.');
        } else {
            $this->error('routes/api.php file already exists.');
        }
    }
}
