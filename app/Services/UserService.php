<?php

namespace App\Services;


use App\Models\UserModel;

class UserService extends Service
{
    public $userModel;

    public function __construct()
    {
        // 初始化
        $this->userModel = new UserModel();
    }

    public function store()
    {
        return 111;
    }

}
