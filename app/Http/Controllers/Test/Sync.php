<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Loop\Stack;
use Illuminate\Http\Request;

class Sync extends Controller
{
   public function handle()
   {
        $info = Stack::handle();


        $flag = Stack::$openCache;

        var_dump($flag);die;
   }
}
