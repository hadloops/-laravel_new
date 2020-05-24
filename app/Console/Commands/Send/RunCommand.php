<?php

namespace App\Console\Commands\Send;

use App\Http\Controllers\Home\IndexController;
use Illuminate\Console\Command;
use App\Loop\Message;

class RunCommand extends Command
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
        echo '222';
        (new IndexController())->run();
        Message::sendMarkdownMsg('loop', '开始执行');

        echo '2333';
    }
}
