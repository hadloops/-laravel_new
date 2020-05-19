<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Jobs\User\SendMessage;
use App\Loop\Log as LoopLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use function Couchbase\defaultDecoder;

class IndexController extends Controller
{
    public function __construct()
    {
    }

    //
    public function index()
    {
        Log::info(sprintf("[%s] [%s]", __CLASS__, date("Y-m-d H:i:s")));
    }


    public function run()
    {

        $time = mt_rand(1, 10);
        Log::error(sprintf("[%s] [%s]", __CLASS__, date("Y-m-d H:i:s")));
        $res = $this->dispatch((new SendMessage($time))->onQueue('user_login')->delay(now()->addSeconds($time)));

        dd($res);


    }

    public function home(Request $request)
    {
        $str = $request->get('u');

        $res    = str_split($str);
        $map    = [
            'A',
            'C',
            'Q',
            'H',
            'K',
            'M',
            'P',
            'G',
            'F',
            'S',
        ];
        $string = '';
        foreach ($res as $value) {
            $string .= $map[$value];
        }

        $q = substr($string, 0, 4);
        $h = substr($string, strlen($string) - 4, 4);
        $z = substr($string, 4, strlen($string) - 8);
        echo $h . $z . $q;


    }

    public function sync(Request $request)
    {
        $str    = $str = $request->get('u');
        $res    = str_split($str);
        $map    = [
            'A',
            'C',
            'Q',
            'H',
            'K',
            'M',
            'P',
            'G',
            'F',
            'S',
        ];
        $map    = array_flip($map);
        $string = '';
        foreach ($res as $value) {
            $string .= $map[$value];
        }

        $q = substr($string, 0, 4);
        $h = substr($string, strlen($string) - 4, 4);
        $z = substr($string, 4, strlen($string) - 8);
        dd($h . $z . $q);

    }



    public function json()
    {
        $str = '{"response":"tRh+RJd7ZCDaqb9HvA5J1X4Ma1xG/nRMMYHHsKyA3kmHz8MCEDclxW3F4UHQ1ns5naOonzzO6ZnH4ubFlTkP9eCI5ODgKDx7Gpvf2hd7nu4IatTShWHwBPoD/IMB/sV4HMkO4sX/fhoglCbBeQ/Cozm2j7aOndM0dFErGZO/EUM=","sign":"HsEj9qdm4gfOduCYw+Pb8Zkx2nWGJTtSer8QTcKSG+Lp33gTX33K8GLNN1QRuwnA8Ug7H/qLrieaNp0FTUk6fulkwuYlk2OHFxj2OW62L9T8DF0SNE955ldlTz1m3NUcoYy25tNwPr97T5OyQ3PnNBZs9ADkFBdkjZFGP4/ppAuDSZwRF6WSh8Tn6JkJbIFw2MXAx7QMOqMR46PTVmuNMtr4MERAnhjXJchiWWJzEvCqXJE68Q98JVXN62IEsXi0xQdRk4h9gjluIVBpwAdQ9JFqCUXPfMlhUoB10lFqyhX2JUHKH2dNRKqS8VENcTkxMpSllS3TxL9y4rU2TZGCPQ=="}';

        var_dump(json_decode($str,true));
    }

    public function test()
    {
        phpinfo();
    }
}
