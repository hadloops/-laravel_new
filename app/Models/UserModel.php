<?php

namespace App\Models;


class UserModel extends Model
{


    # 库连接名，默认库，需要连接其他库，请修改此处并配置.env和database.php文件中的配置
    protected $connection = 'mysql';

    # 表名
    protected $table = 'user_list';

    # 不自动维护时间字段
    public $timestamps = false;
}
