<?php


namespace App\Loop;


class Curl
{
    public static function httpPost($url, $data = '', $return_header = 0, $header = '')
    {
        $curl = curl_init();
        if ( $header ) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header); // 发送头
        }
        curl_setopt($curl, CURLOPT_URL, trim($url));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_POST, true);  // 发送一个常规的Post请求
        if ( $data ) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  // Post提交的数据包
        }

        curl_setopt($curl, CURLOPT_HEADER, $return_header);  // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_TIMEOUT, 10); // 设置超时限制防止死循环
        $result = curl_exec($curl);
        if ( curl_errno($curl) ) {
            $result = curl_error($curl); // 捕抓异常
        }
        curl_close($curl);
        return $result;
    }

    public function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        if ( curl_errno($curl) ) {
            $res = curl_error($curl); // 捕抓异常
        }
        curl_close($curl);
        return $res;
    }

    public function httpsGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        if ( curl_errno($curl) ) {
            $res = curl_error($curl); // 捕抓异常
        }
        curl_close($curl);
        return $res;
    }
}
