<?php

namespace App\Services;


class ReaService extends Service
{


    public function test()
    {
        /**
         * RSA算法属于非对称加密算法,非对称加密算法需要两个秘钥:公开密钥(publickey)和私有秘钥(privatekey).公开密钥和私有秘钥是一对,如果公开密钥对数据进行加密,只有用对应的私有秘钥才能解密;如果私有秘钥对数据进行加密那么只有用对应的公开密钥才能解密.因为加密解密使用的是两个不同的秘钥,所以这种算法叫做非对称加密算法.简单的说就是公钥加密私钥解密,私钥加密公钥解密.
         * 需要给PHP打开OpenSSL模块
         * 生成公钥和私钥的链接:  http://web.chacuo.net/netrsakeypair
         * openssl_pkey_get_public //检查公钥是否可用
         * openssl_public_encrypt //公钥加密
         * openssl_pkey_get_private //检查私钥是否可用
         * openssl_private_decrypt //私钥解密
         *
         */

        /*********************测试公钥*******************************
         * -----BEGIN PUBLIC KEY-----
         * MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCmkANmC849IOntYQQdSgLvMMGm
         * 8V/u838ATHaoZwvweoYyd+/7Wx+bx5bdktJb46YbqS1vz3VRdXsyJIWhpNcmtKhY
         * inwcl83aLtzJeKsznppqMyAIseaKIeAm6tT8uttNkr2zOymL/PbMpByTQeEFlyy1
         * poLBwrol0F4USc+owwIDAQAB
         * -----END PUBLIC KEY-----
         *************************************************************
         ************************测试私钥*****************************
         * -----BEGIN PRIVATE KEY-----
         * MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAKaQA2YLzj0g6e1h
         * BB1KAu8wwabxX+7zfwBMdqhnC/B6hjJ37/tbH5vHlt2S0lvjphupLW/PdVF1ezIk
         * haGk1ya0qFiKfByXzdou3Ml4qzOemmozIAix5ooh4Cbq1Py6202SvbM7KYv89syk
         * HJNB4QWXLLWmgsHCuiXQXhRJz6jDAgMBAAECgYAIF5cSriAm+CJlVgFNKvtZg5Tk
         * 93UhttLEwPJC3D7IQCuk6A7Qt2yhtOCvgyKVNEotrdp3RCz++CY0GXIkmE2bj7i0
         * fv5vT3kWvO9nImGhTBH6QlFDxc9+p3ukwsonnCshkSV9gmH5NB/yFoH1m8tck2Gm
         * BXDj+bBGUoKGWtQ7gQJBANR/jd5ZKf6unLsgpFUS/kNBgUa+EhVg2tfr9OMioWDv
         * MSqzG/sARQ2AbO00ytpkbAKxxKkObPYsn47MWsf5970CQQDIqRiGmCY5QDAaejW4
         * HbOcsSovoxTqu1scGc3Qd6GYvLHujKDoubZdXCVOYQUMEnCD5j7kdNxPbVzdzXll
         * 9+p/AkEAu/34iXwCbgEWQWp4V5dNAD0kXGxs3SLpmNpztLn/YR1bNvZry5wKew5h
         * z1zEFX+AGsYgQJu1g/goVJGvwnj/VQJAOe6f9xPsTTEb8jkAU2S323BG1rQFsPNg
         * jY9hnWM8k2U/FbkiJ66eWPvmhWd7Vo3oUBxkYf7fMEtJuXu+JdNarwJAAwJK0YmO
         * LxP4U+gTrj7y/j/feArDqBukSngcDFnAKu1hsc68FJ/vT5iOC6S7YpRJkp8egj5o
         * pCcWaTO3GgC5Kg==
         * -----END PRIVATE KEY-----
         */


        define('RSA_PUBLIC', '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC49Ic452NS6z5+MPgm8WQIaD/8
fbwoeYbmp3QE8/gidIF1is2QFWTSIj7XPUQ6c25TAHR5e5xSHJZrj3gJ+IIMPhMK
od+8CeTFvEojsGNn3Dkqblm4rJG4gtZBjhbavUnr2nsiHq1RqkvplX//3U8LZXI3
0SFhlJh/EuMjyQDpKwIDAQAB
-----END PUBLIC KEY-----');


        define('RSA_PRIVATE', '-----BEGIN RSA PRIVATE KEY-----
MIICXwIBAAKBgQC49Ic452NS6z5+MPgm8WQIaD/8fbwoeYbmp3QE8/gidIF1is2Q
FWTSIj7XPUQ6c25TAHR5e5xSHJZrj3gJ+IIMPhMKod+8CeTFvEojsGNn3Dkqblm4
rJG4gtZBjhbavUnr2nsiHq1RqkvplX//3U8LZXI30SFhlJh/EuMjyQDpKwIDAQAB
AoGBAKLJhmLNNZx7tMs4qpEMETFdIERJHly5acSFShY57QHWbUNZYcgZLF3PCmRD
ZlPT2Rxw4BM0esfCpZoR8mNEOKCyr8eHlgBuAIWWtHEoAPKrwaTNHjwQLIhaobNj
kLOKLIYfSrhoj5QsIMxa2ct+S15OE1BytrWe/R1anFwb1NmxAkEA3zqtf3P1JZ70
1RxJUrbj2PHipbQY2sereD0BVSKBAOLNlxbF8uOogUmmooi67VD8Y6nE8P0K4a4e
3lkWcvquNQJBANQbcxaXTlWb/1RLdLaNJN15G/VVlPZG4lv1R56hrlu2qDExJTiq
0ANKEXlzB4NumRRc2TWx7Br1rsCd3pxPpd8CQQDPINkfq+7KC6ZNn8OBVmYwRLDy
5Bsz1ZWLKb/0yOE+ezQcf2sJJqiR3k8Z/RjFXyHxL/OnUIQqR7AozC0CXwwJAkEA
imxHlYy1MKWX4llEGAbQ8kChgGCT0I2+GClgziPR+ESiS0g5dFv1WNAzy0DIvHun
J2TY1HQDFC0WGNpudzB7nQJBAMKLUJdiO3vlmygi9ve9IeIQXXNM/hDUr1t3WwFx
pvo+L0pzO3pisr6YLPPqacbOvQbuAjsa5ukMTRDjbuwSSUo=
-----END RSA PRIVATE KEY-----');
        $data  = array(
            'a' => 'a1',
            'b' => 'b',
            'c' => 'c',
            'd' => 'd',
            'e' => 'e1',
            'f' => 'f1',

        );
        $jdata = json_encode($data);
        
        /**
         * 公钥加密
         *
         */
        $key = openssl_pkey_get_public(RSA_PUBLIC);

        if ( !$key ) {
            die('公钥不可用');
        }
//公钥加密
//openssl_public_encrypt 第一个参数只能是string
//openssl_public_encrypt 第二个参数是处理后的数据
//openssl_public_encrypt 第三个参数是openssl_pkey_get_public返回的资源类型
        $return_en = openssl_public_encrypt($jdata, $crypted, $key);
        if ( !$return_en ) {
            return ('加密失败,请检查RSA秘钥');
        }


        $eb64_cry = base64_encode($crypted);
        var_dump($eb64_cry);

        echo "<hr>";

        //私钥解密
        $private_key = openssl_pkey_get_private(RSA_PRIVATE);
        if ( !$private_key ) {
            die('私钥不可用');
        }
        $return_de = openssl_private_decrypt(base64_decode($eb64_cry), $decrypted, $private_key);

        if ( !$return_de ) {
            return ('解密失败,请检查RSA秘钥');
        }

        $arr = json_decode($decrypted, true);

        var_dump($arr);
    }

}
