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
}
