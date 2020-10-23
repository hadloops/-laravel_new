<?php

namespace App\Loop;

class Stack
{
    static $openCache = true;

    public static function handle()
    {
        self::$openCache = false;
    }

}
