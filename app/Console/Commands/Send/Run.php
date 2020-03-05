<?php

namespace App\Console\Commands\Send;

use App\Http\Controllers\Home\IndexController;
use Illuminate\Console\Command;

class Run extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '入队脚本';

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
        (new IndexController())->run();
       // \App\Loop\Message::sendMarkdownMsg('loop', '开始执行');
    }
}
