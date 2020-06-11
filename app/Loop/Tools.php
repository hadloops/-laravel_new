<?php

namespace App\Loop;

use Illuminate\Support\Facades\Log;
use Psr\Log\InvalidArgumentException;

class Tools
{
    /**
     * 生成唯一ID
     *
     * @return string
     */
    public static function commonIdGen()
    {
        mt_srand((double) microtime() * 1000000);
        //return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        return time() . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    /**
     * 过滤特殊字符
     *
     * @param $str
     *
     * @return false|int
     */
    public static function checkString($str)
    {
        $regex = "/(\|\~|\!|\@|\\$|\%|\^|\&|\*|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\;|\'|\`|\=|\\\|！|￥|…|—|「|」|【|】|、|《|》|？|“|”|：|；|·)+/";
        return preg_match($regex, $str);
    }

    /**
     * 价格数据格式化
     *
     * @param $price
     *
     * @return bool|string
     */
    public static function priceFormat($price)
    {
        // 保留两位小数不四舍五入
        return $price ? substr(sprintf("%.3f", $price), 0, -1) : '0.00';
    }

    /**
     * 手机号/电话号码验证
     * 格式：13988888888 或 010-88888888[-123456]，支持最多6位分机号
     *
     * @param $phoneNumber
     *
     * @return bool
     */
    public static function checkPhone($phoneNumber)
    {
        return preg_match('/(^(13[0-9]|15[0-9]|166|17[013678]|18[0-9]|14[457]|19[89])[0-9]{8})$|^(0\d{2,3}\-\d{7,8}(\-\d{1,6})?$)/',
            $phoneNumber);
    }

    /**
     * 科学计数法数字转换为纯数字（excel导表时常用）
     *
     * @param $num
     *
     * @return float|string
     */
    public static function numToStr($num)
    {
        $result = "";
        if ( stripos($num, 'e') === false ) {
            return $num;
        }
        while ($num > 0) {
            $v      = $num - floor($num / 10) * 10;
            $num    = floor($num / 10);
            $result = $v . $result;
        }
        return $result;
    }

    /**
     * 获取 HTML 中的图片地址
     *
     * @param         $content
     * @param string  $order
     *
     * @return mixed|string
     */
    public static function getHtmlImgs($content, $order = 'ALL')
    {
        $content = htmlspecialchars_decode($content);
        $pattern = "/<img.*?src=[\'|\"](.*?(?:[\.gif|\.GIF|\.jpg|\.JPG|\.jpeg|\.JPEG|\.png|\.PNG|\.bmp|\.BMP]))[\'|\"].*?[\/]?>/";
        preg_match_all($pattern, $content, $match);
        if ( isset($match[1]) && !empty($match[1]) ) {
            if ( $order === 'ALL' ) {
                return $match[1];
            }
            if ( is_numeric($order) && isset($match[1][$order]) ) {
                return $match[1][$order];
            }
        }
        return '';
    }

    /*
        商品价格计算规则：
            成本未超500
                正常售价（成本+8）÷ 0.75
                vip售价（成本+8）÷ 0.9
                渠道（成本×0.2）+ 正常售价

            成本超500
                正常价格：成本 + 8 然后 ÷ 0.9
                vip价格：成本 + 8 然后 ÷ 0.95
                渠道（成本 × 0.2）+ 正常售价
    */
    /**
     * 商品正常价格计算
     *
     * @param $currentCost
     *
     * @return bool|string
     */
    public static function CalculateNormalPrice($currentCost)
    {
        if ( $currentCost <= 500 ) {
            // 成本500以内
            $price = bcdiv(bcadd($currentCost, 8, 2), 0.75, 2);
        } else {
            // 成本500以上
            $price = bcdiv(bcadd($currentCost, 8, 2), 0.9, 2);
        }
        // 四舍五入取整
        return round($price);
    }

    /**
     * 商品VIP价格计算
     *
     * @param $currentCost
     *
     * @return bool|string
     */
    public static function CalculateVipPrice($currentCost)
    {
        if ( $currentCost <= 500 ) {
            // 成本500以内
            $price = bcdiv(bcadd($currentCost, 8, 2), 0.9, 2);
        } else {
            // 成本500以上
            $price = bcdiv(bcadd($currentCost, 8, 2), 0.95, 2);
        }
        // 四舍五入取整
        return round($price);
    }

    /**
     * 商品渠道价格计算
     *
     * @param $currentCost
     * @param $chinaGoodsPrice
     *
     * @return bool|string
     */
    public static function CalculateChannelPrice($currentCost, $chinaGoodsPrice)
    {
        $price = bcadd(bcmul($currentCost, 0.2, 2), $chinaGoodsPrice, 2);
        // 四舍五入取整
        return round($price);
    }

    /**
     * 检测远程图片是否存在
     *
     * @param $url
     *
     * @return bool
     */
    public static function imgExists($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $contents = curl_exec($ch);
        if ( $contents !== false ) {
            // 释放内存
            unset($contents);
            return true;
        } else {
            return false;
        }
    }


    public static function curl($url, $params = false, $ispost = 0, $https = 0)
    {
        $httpInfo = array();
        $ch       = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT,
            'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ( $https ) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        }
        if ( $ispost ) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ( $params ) {
                if ( is_array($params) ) {
                    $params = http_build_query($params);
                }
                // 此处就是参数的列表,给你加了个?
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);
        if ( $response === false ) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }

    /**
     * 验证颜色格式
     *
     * @param        $input
     * @param string $name
     */
    public static function checkColorFormat($input, $name = '')
    {
        if ( !preg_match('/^\#[a-zA-Z0-9]+$/i', $input) ) {
            throw new \InvalidArgumentException($name . '颜色格式不正确');
        }
    }

    /**
     * 检查是否设置了指定的参数字段
     *
     * @param array $fields
     * @param array $params
     */
    public static function checkIsSet(array $fields, array $params)
    {

        foreach ($fields as $field) {
            if ( !array_key_exists($field, $params) ) {
                throw new \InvalidArgumentException("参数未定义: " . $field);
            }
        }
    }

    /**
     * 获取数组中指定的值，并简单格式化
     *
     * @param mixed  $params
     * @param string $field
     * @param null   $default
     * @param bool   $format
     *
     * @return bool|float|int|mixed|string
     * @deprecated
     *
     */
    public static function getValue(
        $params,
        string $field,
        $default = null,
        $format = false
    ) {
        // not expect
        if ( !is_array($params) ) {
            $params = [];
        }

        $params[$field] = array_key_exists(
            $field,
            $params
        ) ? $params[$field] : $default;

        if ( $format === false || !is_string($format) ) {
            return $params[$field];
        }

        switch ($format) {
            case 'int':
                return intval($params[$field]);
                break;
            case 'str':
                return strval($params[$field]);
                break;
            case 'float':
                return floatval($params[$field]);
                break;
            case 'bool':
                return boolval($params[$field]);
                break;
            default:
                return $params[$field];
                break;
        }
    }

    /**
     * 获取数组中指定的值，支持强制类型校验
     *
     * @param        $params
     * @param string $field
     * @param null   $default
     * @param bool   $strictType
     *
     * @return mixed
     */
    public static function getValueV2(
        $params,
        string $field,
        $default = null,
        $strictType = false
    ) {
        // not expect
        if ( !is_array($params) ) {
            $params = [];
        }

        $params[$field] = array_key_exists(
            $field,
            $params
        ) ? $params[$field] : $default;

        if ( $strictType === false || !is_string($strictType) ) {
            return $params[$field];
        }

        // strict type check
        switch ($strictType) {
            case 'int':
                if ( !is_int($params[$field]) ) {
                    throw new \InvalidArgumentException('field is not int type: ' . $field);
                }
                break;
            case 'str':
                if ( !is_string($params[$field]) ) {
                    throw new \InvalidArgumentException('field is not string type: ' . $field);
                }
                break;
            case 'float':
                if ( !is_float($params[$field]) ) {
                    throw new \InvalidArgumentException('field is not float type: ' . $field);
                }
                break;
            case 'bool':
                if ( !is_bool($params[$field]) ) {
                    throw new \InvalidArgumentException('field is not bool type: ' . $field);
                }
                break;
            case 'arr':
                if ( !is_array($params[$field]) ) {
                    throw new \InvalidArgumentException('field is not array type: ' . $field);
                }
                break;
            default:
                throw new \InvalidArgumentException('un support strict type: ' . $strictType);
                break;
        }

        return $params[$field];
    }

    /**
     * 二维数组按照指定key去重
     *
     * @param array $rows
     * @param       $uniqueKey
     *
     * @return array
     */
    public static function arrToUnique(array $rows, $uniqueKey)
    {
        $result = [];
        foreach ($rows as $row) {
            if ( empty($row[$uniqueKey]) ) {
                continue;
            }

            $result[$row[$uniqueKey]] = $row;
        }

        return array_values($result);
    }

    /**
     * 校验url
     *
     * @param        $url
     * @param string $subject
     *
     * @return bool
     * @throws InvalidArgumentException
     *
     */
    public static function verifyUrl($url, $subject = '')
    {
        $pattern = '/^(\/\w*){1,}\/?\??[^\s]*$/i';
        if ( !preg_match($pattern, $url) ) {
            throw new \InvalidArgumentException($subject . ' url格式不正确: ' . $url);
        }

        return true;
    }

    /**
     * 生成 Excel Writer
     *
     * @param array  $dataSets
     * @param string $sheetTitle
     *
     * @return \PHPExcel_Writer_Excel2007
     */
    public static function genExcelWriterByArr(array $dataSets, $sheetTitle = 'default')
    {
        $objPHPExcel = new \PHPExcel();
        $objWriter   = new \PHPExcel_Writer_Excel2007($objPHPExcel);

        // 设置当前的sheet @TODO 这边没有设置任何格式
        $objPHPExcel->setActiveSheetIndex(0);

        // 设置sheet的name
        $objPHPExcel->getActiveSheet()->setTitle($sheetTitle);
        $row = 1;
        foreach ($dataSets as $dataSet) {
            $column = 0;
            foreach ($dataSet as $value) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $value);
                $column++;
            }
            $row++;
        }

        return $objWriter;
    }

    /**
     * 解析 Excel 文件内容
     *
     * @param $excel
     *
     * @return array
     */
    public static function getArrFromExcel($excel)
    {
        try {
            $inputFileType = \PHPExcel_IOFactory::identify($excel);
            $objReader     = \PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel   = $objReader->load($excel);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error loading file: ' . $e->getMessage());
        }

        $sheet         = $objPHPExcel->getSheet(0);
        $highestRow    = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $rows = [];
        for ($row = 1; $row <= $highestRow; $row++) {
            $lines = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                null,
                true,
                true, true);

            $line = current($lines);
            if ( $line === false ) {
                Log::error('line parse error: ' . json_encode($lines));
                continue;
            }

            $rows[] = $line;
        }

        return $rows;
    }

    /**
     * 将一个子元素为对象的数组转换为纯数组（仅获取对象中的成员属性）
     *
     * @param $rows
     *
     * @return array
     */
    public static function ObjectVarsToArray($rows)
    {
        foreach ($rows as $k => & $row) {
            if ( !($row instanceof \stdClass) ) {
                unset($rows[$k]);
                continue;
            }

            $row = get_object_vars($row);
        }

        return $rows;
    }

    /**
     * 验证时间是否合法
     *
     * @param        $value
     * @param string $format
     *
     * @return bool
     */
    public static function isDateValid($value, $format = 'Y-m-d')
    {
        if ( !is_string($value) && !is_numeric($value) ) {
            return false;
        }

        $date = \DateTime::createFromFormat('!' . $format, $value);

        return $date && $date->format($format) == $value;
    }

    /**
     * 对二维数组按照多列进行排序
     *
     * @param $rowSet
     * @param $args
     *
     * @return mixed
     */
    public static function sortByMultiCols($rowSet, $args)
    {
        $sortArray = array();
        $sortRule  = '';
        foreach ($args as $sortField => $sortDir) {
            foreach ($rowSet as $offset => $row) {
                $sortArray[$sortField][$offset] = $row[$sortField];
            }
            $sortRule .= '$sortArray[\'' . $sortField . '\'], ' . $sortDir . ', ';
        }
        if ( empty($sortArray) || empty($sortRule) ) {
            return $rowSet;
        }
        eval('array_multisort(' . $sortRule . '$rowSet);');
        return $rowSet;
    }

    /**
     * 获取文件后缀
     *
     * @param string $filename
     *
     * @return string
     */
    public static function getFileExt(string $filename)
    {
        $segments = explode('.', $filename);
        if ( count($segments) === 1 ) {
            return '';
        }

        return count($segments) === 1 ? '' : array_pop($segments);
    }
}
