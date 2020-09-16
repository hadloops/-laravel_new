<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Demo extends Controller
{
    //
    public function demo()
    {

        $demo = new \stdClass();

        if ( empty((array) $demo) ) {
            return 'empty';
        } else {
            return 'true';
        }
    }


    /**
     *  获取请求中变量的值
     *
     * @param Request $request  请求
     * @param         $varName  变量名
     *
     * @return mixed  变量值
     */
    public function varValue(Request $request, $varName)
    {
        return $request->get($varName, '');
    }
}
