<?php

namespace App\Console\Commands\MakeJob;

use App\Jobs\Command\RunCommandJob;
use App\Loop\Message;
use Illuminate\Console\Command;

class RunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:test:job';

    /**
     * The console command description
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //

        dispatch((new RunCommandJob())->onQueue('make:job'));


    }
}
