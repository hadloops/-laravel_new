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
        $this->demo('test.go');

    }

    public function sync($goodsId, $skuId)
    {

        var_dump(get_defined_vars());
    }


    public function demo($file)
    {
        $path = public_path("/../script/");
        if ( !file($path . $file) ) {

            info(sprintf('[%s][%s] go file is not', __CLASS__, __FUNCTION__, $file));
        }

        echo exec("cd $path && go build $file");
    }

}
