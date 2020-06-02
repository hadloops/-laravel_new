<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    //
//
//    public function index(Request $request)
//    {
//
//        $sign = $_SERVER;
//
////        var_dump($sign);s
////        die;
//
//
//        var_dump(self::em_getallheaders());
//        die;
//        $bodyData = @file_get_contents('php://input');
////将获取到的值转化为数组格式
//        $bodyData = json_decode($bodyData, true);
//
//        var_dump($bodyData);
//    }
//
//    public function em_getallheaders()
//    {
//        foreach ($_SERVER as $name => $value) {
//            if ( substr($name, 0, 5) == 'HTTP_' ) {
//                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
//            }
//        }
//        return $headers;
//    }

    public function index()
    {
        if ('1e3' == '1000') echo 'LOL';
    }


}
