<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync';

    /**
     * The console command description.
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
     * @return int
     */
    public function handle()
    {
        //
        $this->demo('select');

    }


    public function demo($file)
    {
        $path = public_path("/../../go_script/");

        if ( !file($path . $file) ) {
            echo '文件不存在';
            info(sprintf('[%s][%s] go file is not', __CLASS__, __FUNCTION__, $file));
            exit();
        }

        exec("cd $path && chmod a+x $file && ./$file ");
    }

}
