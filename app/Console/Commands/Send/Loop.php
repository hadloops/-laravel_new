<?php

namespace App\Console\Commands\Send;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

class Loop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'loop脚本';

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
        Log::info(sprintf("[%s][%s]", __CLASS__, __FUNCTION__));
    }
}
