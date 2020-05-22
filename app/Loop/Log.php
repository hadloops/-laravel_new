<?php

namespace App\Loop;

use Illuminate\Support\Facades\Log as BaseLog;

class Log
{
    public static function error($info)
    {
        $info = str_replace(['\\'], '_', $info);
        return BaseLog::info($info);
    }

    public function debug($info)
    {
        $info = str_replace(['\\'], '_', $info);
        return BaseLog::debug($info);
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        // $logObj = new BaseLog();
        foreach ($arguments as &$value) {
            $value = str_replace(['\\'], '_', $value);
        }
        $logObj = app('log');
        return call_user_func_array(array($logObj, $name), $arguments);
    }
}
