<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Jobs\Command\RunCommandJob;
use App\Loop\Stack;
use Illuminate\Http\Request;

class Sync extends Controller
{
    public function handle()
    {


        $str = '{
    "top_data": {
        "goods_list": [
            {
                "item_type": "",
                "logo_url": "",
                "url_xcx": "",
                "url_h5": "",
                "act_id": "",
                "act_type": "",
                "sku_id": "",
                "goods_id": "",
                "is_related": ""
            }
        ],
        "goods_data": {
            "item_type": "",
            "logo_url": "",
            "url_xcx": "",
            "url_h5": "",
            "act_id": "",
            "act_type": "",
            "sku_id": "",
            "goods_id": "",
            "is_related": ""
        }
    },
    "bottom_data": {
        "small_list": {
            "is_flip": "",
            "list": [
                [
                    {
                        "item_type": "",
                        "logo_url": "",
                        "url_xcx": "",
                        "url_h5": "",
                        "act_id": "",
                        "act_type": "",
                        "is_related": "",
                        "short_name": "",
                        "goods_id": "",
                        "sku_id": ""
                    }
                ]
            ]
        },
        "big_list": {
            "is_flip": "",
            "list": [
                [
                    {
                        "item_type": "",
                        "logo_url": "",
                        "url_xcx": "",
                        "url_h5": "",
                        "act_id": "",
                        "act_type": "",
                        "is_related": "",
                        "short_name": "",
                        "goods_id": "",
                        "sku_id": ""
                    }
                ]
            ]
        }
    }
}';

        $arr = json_decode($str, true);


        dd($arr);

//        $info = Stack::handle();
//
//
//        $flag = Stack::$openCache;
//
//        var_dump($flag);die;
    }

    public function makeJob()
    {
        $time = mt_rand(1, 20);
        $info = dispatch((new RunCommandJob())->onQueue('make:job')->delay(now()->addSeconds($time)));

        dd($info);
    }

    public function m()
    {
        $str = " 🥶😳💆🏼‍♀️😩😏😭😊😂❤️  \n    ❤️❤️❤️❤️ \n\n
        😭😏😁☺️🧔🏿🎶😒👌🏻😍😒👌\n
              😔😉😌💁🏻🙈😎👀😑😴😄😀\n
              😃😄😁😆😅😂🤣😂😅😆🤣\n
              ☺️😊🙃🙂😇 \n";


        echo $str;


    }

    public function t()
    {
        for ($t = 0; $t < 360; $t++) {
            $y     = 2 * cos($t) - cos(2 * $t); //笛卡尔心形曲线函数
            $x     = 2 * sin($t) - sin(2 * $t);
            $x     += 3;
            $y     += 3;
            $x     *= 70;
            $y     *= 70;
            $x     = round($x);
            $y     = round($y);
            $str[] = $x;
            $y     = $y + 2 * (180 - $y);//图像上下翻转
            $x     = $y;
            $str[] = $x;
        }
        $im    = imagecreate(400, 400);//创建画布400*400
        $black = imagecolorallocate($im, 0, 0, 0);
        $red   = imagecolorallocate($im, 255, 0, 0);//设置颜色
        imagepolygon($im, $str, 360, $red);
        imagestring($im, 5, 190, 190, "HRC", $red);//输出字符串
        header('Content-type:image/gif');//通知浏览器输出的是gif图片
        imagegif($im);//输出图片
        imagedestroy($im);//销毁
    }
}
