<?php

/**
 *
 */

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(UserService $userService)
    {
        return $userService->store();
    }

    public function test()
    {


        $list   = [];
        $list[] = ['name' => '张三', 'phoneNumber' => '15010095962', 'extendInfos' => ['测试' => "111", "卡号" => 111]];


//        $data = urldecode(json_encode($list));
//
//        var_dump(json_decode($data,true));die;

        echo json_encode($list);die;
        return response($list);

    }

}
