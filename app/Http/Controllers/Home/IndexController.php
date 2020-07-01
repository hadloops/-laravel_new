<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Jobs\User\SendMessage;
use App\Loop\Log as LoopLog;
use App\Services\AesService;
use App\Services\ReaService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __construct()
    {
    }

    //
    public function index()
    {
        echo $_SERVER['SERVER_PORT'];

        die;
        //解密的密文
        //$sign = "H6O61Lztu/NJtQjW0MXfFrPCt834BP7WwrNMMnfDOqIwvzuUFeseZAjQQ4OClLkdKFs2Iij+z2q1vHuMNx19GJuq6j8LeVE9cu1PZ2GWmXHY0Z08CaVZitB4IjOfijIJYXZe+hopLbVClvslNjLFXfunLJix6LfWZYCGxc3MoyuprZ+RDqlpzMc+iSy1k2r5BSNj0Jz30GFuZRbyRvt6Xw==";

        $sign = 'sWPvxBz1Bg50237jx+PdxNCbkZGiSM3Mwhsfieomb6TPQ6ZRAGYhiHFhPK6y1Fp3HSMJfiWSdK3apQkvR4vyX11N0s70IKaVidf2Lg5tTqrMzXZQNO+UBk3cwkjjAp7ptikgXz3PrHm6exeK6x0rVycBde6B9Rwp8/ZqfvbcOxzBWrClg+2EBFMTe83nNidgM7MKuIuWgIRojEc4aPCStg==';
        //向量
        //$iv = "B393QpfxWodwG410W7JBNQ==";
        $iv = "LCr7pqkpJoB+Bl5vOgCB3w==";

        //sessionKey
        //$sessionKey = '0s14WcTa2jxMuzB4pGPD+g==';
        $sessionKey = 'rM6SFASdzbU7EHqESVR6kg==';


        $data = openssl_decrypt(base64_decode($sign), 'AES-128-CBC', base64_decode($sessionKey), OPENSSL_RAW_DATA,
            base64_decode($iv));

        var_dump($data);

    }


    public function run()
    {
        $time = mt_rand(1, 10);
        info(sprintf("[%s] [%s]", __CLASS__, date("Y-m-d H:i:s")));
        dispatch((new SendMessage($time))->onQueue('user_login')->delay(now()->addSeconds($time)));


    }

    public function home(Request $request)
    {
        $str = $request->get('u');

        $res    = str_split($str);
        $map    = [
            'A',
            'C',
            'Q',
            'H',
            'K',
            'M',
            'P',
            'G',
            'F',
            'S',
        ];
        $string = '';
        foreach ($res as $value) {
            $string .= $map[$value];
        }

        $q = substr($string, 0, 4);
        $h = substr($string, strlen($string) - 4, 4);
        $z = substr($string, 4, strlen($string) - 8);
        echo $h . $z . $q;


    }

    public function sync(Request $request)
    {
        $str    = $str = $request->get('u');
        $res    = str_split($str);
        $map    = [
            'A',
            'C',
            'Q',
            'H',
            'K',
            'M',
            'P',
            'G',
            'F',
            'S',
        ];
        $map    = array_flip($map);
        $string = '';

        foreach ($res as $value) {
            $string .= $map[$value];
        }
        $q   = substr($string, 0, 4);
        $h   = substr($string, strlen($string) - 4, 4);
        $z   = substr($string, 4, strlen($string) - 8);
        $obj = new LoopLog();
        $obj->error(sprintf("[%s]", __CLASS__));
        dump($str, $h . $z . $q);


    }


    public function json()
    {
        $str = '{"response":"tRh+RJd7ZCDaqb9HvA5J1X4Ma1xG/nRMMYHHsKyA3kmHz8MCEDclxW3F4UHQ1ns5naOonzzO6ZnH4ubFlTkP9eCI5ODgKDx7Gpvf2hd7nu4IatTShWHwBPoD/IMB/sV4HMkO4sX/fhoglCbBeQ/Cozm2j7aOndM0dFErGZO/EUM=","sign":"HsEj9qdm4gfOduCYw+Pb8Zkx2nWGJTtSer8QTcKSG+Lp33gTX33K8GLNN1QRuwnA8Ug7H/qLrieaNp0FTUk6fulkwuYlk2OHFxj2OW62L9T8DF0SNE955ldlTz1m3NUcoYy25tNwPr97T5OyQ3PnNBZs9ADkFBdkjZFGP4/ppAuDSZwRF6WSh8Tn6JkJbIFw2MXAx7QMOqMR46PTVmuNMtr4MERAnhjXJchiWWJzEvCqXJE68Q98JVXN62IEsXi0xQdRk4h9gjluIVBpwAdQ9JFqCUXPfMlhUoB10lFqyhX2JUHKH2dNRKqS8VENcTkxMpSllS3TxL9y4rU2TZGCPQ=="}';

        var_dump(json_decode($str, true));
    }

    public function test()
    {
        $str = 'vnjjA2zifsuLo9lDVb9kJwgvwGvihcxvLx6K1VnB7uwf0walfV79RMffS2a8+j6MNgmXRBb7aPcpEWKDCCxQ9A==","sign":"nBXf6bCWxEjkgHI96dEy+AIBwSiDWr0P/QHc/f1JOoCcFGyCD+gH2bSGZQF6aXk31i0utvN+Ve9wYROsWaqYGXUmnS5DCpDetq8uAhEpXYvhjnGgrhBi+WLJWjrodCl5/r7wlnxg/uyDVlkmvd4upd1ocosODh7fdTuOko6+q07LOjJaJWvlDKV8huLCA4EVZnowEvlBnqG7LqdBlStrYdgOCnULZ28qN08E5G5CNkjxEoOfvxfCIJJW3GWJVgGr7mnD0tICQ5GE8R+LocqVhuYbvmyEqtU7SYbv2e7dIB33ltgwnIECtnsXdAhyGhHDCyqKaGiEYGSGmlruF02XgA==';

        $key    = 'beT3gYsuC9ElbZt5wJesUw==';
        $aesKey = base64_decode($key);
        $iv     = 0;

        $aesIV = base64_decode($iv);

        $aesCipher = base64_decode($str);

        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        dd($result);


    }


    public function test2()
    {

        $config = [
            'key'    => '', //加密key
            'iv'     => md5(time() . uniqid(), true), //保证偏移量为16位
            'method' => 'AES-128-CBC' //加密方式  # AES-256-CBC等

        ];


        $obj = new AesService($config);
        $str = '6ufe6mt+nFW1Cg4l1piJBqvfPMpouWwHTl4vJTRr5ae+gWoJC61HAfhuPWjZfr6+pRk2HieCdxzVTOcXi5Z8AWMrW7Fdr8jy6S1iJenWO0iraMfYfDXZ3a4mB72Gnwo6wH9RVGBrzn0N2iMv2nH0WwUeGz7NKA4adELGam3DSKw9GnwVOOMa4wP4iUlIlQeGxNW1+rWJ6XaotNi0y+kP2w==';


        try {
            $data = $obj->aesDe($str);
            LoopLog::error(sprintf("[%s][%s]", __CLASS__, $data));
        } catch (\Exception $exception) {
            $msg = $exception->getMessage();

            LoopLog::error(sprintf("[%s][%s]", __CLASS__, $msg));
        }
    }


    public function user(Request $request)
    {
        dump(mb_strlen('{\\\"order_id\\\":115905613749599,\\\"status\\\":\\\"MERCHANT_PAID\\\"}'));


        dump('mobile_device_code_redis_key_' . md5($request->get('u', '')));
    }

    public function stu()
    {
        $bodyData = file_get_contents('php://input');
    }


    public function aes()
    {
        $obj = new ReaService();

        return $obj->test();
    }
}
