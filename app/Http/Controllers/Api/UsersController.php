<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    //

    public function rpush()
    {
        $redis = app('redis');

        $redis->rpush('wx-test', ...['1', 1,2,2,1,2,3,4,5,5,2,6,7,8,6,5,4,3,3,2,3,3,3,3,33,3,3]);
    }
}
