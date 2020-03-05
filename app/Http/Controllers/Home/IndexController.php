<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Jobs\User\SendMessage;
use App\Loop\Log;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    //
    public function index()
    {
        Log::error(sprintf("[%s] [%s]", __CLASS__, date("Y-m-d H:i:s")));
    }


    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            $time = mt_rand(1, 10);
            //Log::error(sprintf("[%s] [%s]", __CLASS__, date("Y-m-d H:i:s")));

            $this->dispatch((new SendMessage($time))->onQueue('user_login')->delay(now()->addSeconds($time)));
        }

    }
}
