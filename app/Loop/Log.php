<?php

namespace App\Loop;

use Illuminate\Support\Facades\Log as BaseLog;

class Log
{
    public static function error($info)
    {
        return BaseLog::info($info);
    }

    public function debug($info)
    {
        return BaseLog::debug($info);
    }
}
