<?php
/**
 * Created by PhpStorm.
 * UserModel: sync
 * Date: 2019-11-03
 * Time: 14:26
 */

namespace App\Loop;

use App\Loop\Curl;

class Message
{
    public static function sendMarkdownMsg($title, $content, $atAll = false)
    {
        $payload = [
            'msgtype'  => 'markdown',
            'markdown' => [
                "title" => '通知' . $title,
                "text"  => $content,
            ],
            'at'       => [
                'isAtAll' => $atAll
            ]
        ];
        $url     = "https://oapi.dingtalk.com/robot/send?access_token=" . self::getToken();
        $header  = [
            'Content-Type: application/json;charset=utf-8',
        ];
        self::run($url, json_encode($payload), $header);
    }

    public static function run($url, $data = null, $header = null)
    {
        //请求 URL，返回该 URL 的内容
        $ch = curl_init(); // 初始化curl
        curl_setopt($ch, CURLOPT_URL, $url); // 设置访问的 URL
        curl_setopt($ch, CURLOPT_HEADER, 0); // 放弃 URL 的头信息
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回字符串，而不直接输出
        // Add Headers?
        if ( $header ) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        // Https ?
        if ( preg_match('/^https/', $url) ) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 不做服务器的验证
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 做服务器的证书验证
        }
        // POST method?
        if ( $data ) {
            curl_setopt($ch, CURLOPT_POST, true); // 设置为 POST 请求
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // 设置POST的请求数据
        }

        // 模拟重定向
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // gzip 解压
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        $content = curl_exec($ch); // 开始访问指定URL
        curl_close($ch); // 关闭 cURL 释放资源
        var_dump($content);
        die;
        return $content;
    }

    /**
     * @param $to
     * @param $title
     * @param $content
     *
     * @return bool|string
     */
    public static function sendMial($to, $title, $content)
    {
        $url = 'http://msg.minqin58.com/mail.php';

        $data['to']      = $to;
        $data['tittle']  = $title;
        $data['content'] = $content;
        $header          = [
            'Content-Type: application/json;charset=utf-8',
        ];
        return Curl::httpPost($url, $data, $header);
    }

    public static function getToken()
    {
        $map = [
            env('DING_DING_TOKEN'),
            env('DING_DING_TOKEN1'),
            env('DING_DING_TOKEN2'),
            env('DING_DING_TOKEN3'),

        ];
        return $map[array_rand($map)];
    }
}
