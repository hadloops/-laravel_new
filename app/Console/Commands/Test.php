<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't';

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

        $str = '{"20200521":"2","20200522":"3","20200523":"1","20200525":"3","20200526":"2","20200527":"1","20200529":"3","20200530":"2","20200531":"3","20200601":"1","20200603":"2","20200604":"1","20200606":"1","20200607":"1","20200608":"2","20200609":"5","20200610":"2","20200611":"3","20200613":"3","20200614":"1","20200621":"1","20200623":"1","20200624":"1","20200628":"2","20200701":"1","20200710":"2","20200715":"1","20200718":"1","20200721":"1","20200723":"1","20200726":"1","20200728":"1","20200729":"1","20200809":"1","20200811":"1"}';


        $arr = json_decode($str,true);



        $file = base_path().'/wg.csv';
        $f =fopen($file,'a+');

        foreach ($arr as $key=>$value){


            fputcsv($f,[$key,$value]);
        }

        fclose($f);



    }
}
