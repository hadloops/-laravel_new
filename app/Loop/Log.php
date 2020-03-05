<?php

namespace App\Loop;

class Log
{
    public static function error($info)
    {
        return \Log::info($info);
    }
}
