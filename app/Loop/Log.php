<?php

namespace App\Loop;

use Illuminate\Support\Facades\Log as BaseLog;

class Log
{
//    public static function error($info)
//    {
//        return BaseLog::info($info);
//    }
//
//    public function debug($info)
//    {
//        return BaseLog::debug($info);
//    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
       // $logObj = new BaseLog();
        $logObj = app('log');
        return call_user_func_array(array($logObj, $name), $arguments);
    }
}
