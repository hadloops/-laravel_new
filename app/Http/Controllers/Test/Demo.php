<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Demo extends Controller
{
    //
    static $entranceCache;

    public function demo()
    {

        //判断是否存在缓存
        $cacheKey = sprintf("entrance_%s", 1);

        if ( !is_null(self::$entranceCache) && array_key_exists($cacheKey, self::$entranceCache) ) {
            $data         = self::$entranceCache[$cacheKey];
            $data['from'] = 'cache';

            return $data;
        }

        $data = ['name' => '张三', 'from' => 'db'];

        //设置缓存
        self::$entranceCache[$cacheKey] = $data;
        return response()->json($data);


    }


    public function removeEmoji($message)
    {
        $message = json_encode($message);
        return json_decode(preg_replace("#(\\\ud[0-9a-f]{3})#i", "", $message), true);
    }


    /**
     *  获取请求中变量的值
     *
     * @param Request $request  请求
     * @param         $varName  变量名
     *
     * @return mixed  变量值
     */
    public function varValue(Request $request, $varName)
    {
        return $request->get($varName, '');
    }
}
