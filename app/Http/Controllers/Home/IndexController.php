<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Jobs\User\SendMessage;
use App\Loop\Log as LoopLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller
{
    //
    public function index()
    {
        setcookie('ali_open_id',1, time()+86400*30, '/', $_SERVER['SERVER_NAME']);


        var_dump($_COOKIE,1);die;
        Log::info(sprintf("[%s] [%s]", __CLASS__, date("Y-m-d H:i:s")));
    }


    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            $time = mt_rand(1, 10);
            Log::error(sprintf("[%s] [%s]", __CLASS__, date("Y-m-d H:i:s")));

            $this->dispatch((new SendMessage($time))->onQueue('user_login')->delay(now()->addSeconds($time)));
        }

    }
}
