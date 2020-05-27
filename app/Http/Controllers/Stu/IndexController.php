<?php

namespace App\Http\Controllers\Stu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    //

    public function index(Request $request)
    {

        $sign = $_SERVER;

        var_dump($sign);die;



        var_dump($request->all());die;
        $bodyData = @file_get_contents('php://input');
//将获取到的值转化为数组格式
        $bodyData = json_decode($bodyData,true);

        var_dump($bodyData);
    }
}
