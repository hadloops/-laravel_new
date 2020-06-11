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
     * @param  string $order
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

    /**
     * 广告法敏感词验证
     *
     * @param $title
     *
     * @return bool
     */
    public static function adWordDisabled($title)
    {
        $words  = [
            '国家级',
            '世界级',
            '最高级',
            '第一',
            '唯一',
            '首个',
            '首选',
            '顶级',
            '国家级产品',
            '填补国内空白',
            '独家',
            '首家',
            '最新',
            '最先进',
            '第一品牌',
            '金牌',
            '名牌',
            '优秀',
            '顶级',
            '独家',
            '全网销量第一',
            '全球首发',
            '全国首家',
            '全网首发',
            '世界领先',
            '顶级工艺',
            '王牌',
            '销量冠军',
            'NO1',
            'Top1',
            '极致',
            '永久',
            '王牌',
            '掌门人',
            '领袖品牌',
            '独一无二',
            '绝无仅有',
            '史无前例',
            '万能',
            '最高',
            '最低',
            '最',
            '最具',
            '最便宜',
            '最新',
            '最先进',
            '最大程度',
            '最新技术',
            '最先进科学',
            '最佳',
            '最大',
            '最好',
            '最大',
            '最新科学',
            '最新技术',
            '最先进加工工艺',
            '最时尚',
            '最受欢迎',
            '最先',
            '绝对值',
            '绝对',
            '大牌',
            '精确',
            '超赚',
            '领导品牌',
            '领先上市',
            '巨星',
            '著名',
            '奢侈',
            '世界',
            '全国',
            '大品牌',
            '100%',
            '国际品质',
            '高档',
            '正品',
            '国家级',
            '世界级',
            '最高级最佳',
            '随时结束',
            '仅此一次',
            '随时涨价',
            '马上降价',
            '最后一波',
            '领导人',
            '机关',
            '质量免检',
            '无需国家质量检测',
            '免抽检',
            '特效',
            '高效',
            '全效',
            '强效',
            '速效',
            '速白',
            '一洗白',
            '天见效',
            '周期见效',
            '超强',
            '激活',
            '全方位',
            '全面',
            '安全',
            '无毒',
            '溶脂',
            '吸脂',
            '燃烧脂肪',
            '瘦身',
            '瘦脸',
            '瘦腿',
            '减肥',
            '延年益寿',
            '提高记忆力',
            '保护记忆力',
            '提高肌肤抗刺激',
            '消除',
            '清除',
            '化解死细胞',
            '去除皱纹',
            '祛除皱纹',
            '平皱',
            '修复断裂弹性力',
            '修复断裂弹性纤维',
            '止脱',
            '采用新型着色机理永不褪色',
            '迅速修复受紫外线伤害的肌肤',
            '更新肌肤',
            '破坏黑色素细胞',
            '阻断黑色素的形成',
            '阻碍黑色素的形成',
            '丰乳',
            '丰胸',
            '使乳房丰满',
            '预防乳房松弛下垂',
            '改善睡眠',
            '促进睡眠',
            '舒眠',
            '老字号',
            '中国驰名商标',
            '特供',
            '专供',
            '点击',
            '恭喜获奖',
            '全民免单',
            '点击有惊喜',
            '点击获取',
            '点击试穿',
            '领取奖品',
            '非转基因更安全',
            '秒杀',
            '抢爆',
            '再不抢就没了',
            '不会再便宜了',
            '错过就没机会了',
            '万人疯抢',
            '抢疯了',
            '全面调整人体内分泌平衡',
            '增强免疫力',
            '提高免疫力',
            '助眠',
            '失眠',
            '滋阴补阳',
            '壮阳',
            '消炎',
            '可促进新陈代谢',
            '减少红血丝',
            '产生优化细胞结构',
            '修复受损肌肤',
            '治愈',
            '抗炎',
            '活血',
            '解毒',
            '抗敏',
            '脱敏',
            '减肥',
            '清热解毒',
            '清热袪湿',
            '治疗',
            '除菌',
            '杀菌',
            '抗菌',
            '灭菌',
            '防菌',
            '消毒',
            '排毒',
            '防敏',
            '柔敏',
            '舒敏',
            '缓敏',
            '脱敏',
            '褪敏',
            '改善敏感肌肤',
            '改善过敏现象',
            '降低肌肤敏感度',
            '镇定',
            '镇静',
            '理气',
            '行气',
            '活血',
            '生肌肉',
            '补血',
            '安神',
            '养脑',
            '益气',
            '通脉',
            '胃胀蠕动',
            '利尿',
            '驱寒解毒',
            '调节内分泌',
            '延缓更年期',
            '补肾',
            '祛风',
            '生发',
            '防癌',
            '抗癌',
            '祛疤',
            '降血压',
            '防治高血压',
            '治疗',
            '改善内分泌',
            '平衡荷尔蒙',
            '防止卵巢及子宫的功能紊乱',
            '去除体内毒素',
            '吸附铅汞',
            '除湿',
            '润燥',
            '治疗腋臭',
            '治疗体臭',
            '治疗阴臭',
            '美容治疗',
            '消除斑点',
            '斑立净',
            '无斑',
            '治疗斑秃',
            '逐层减退多种色斑',
            '妊娠纹',
            '毛发新生',
            '毛发再生',
            '生黑发',
            '止脱',
            '生发止脱',
            '脂溢性脱发',
            '病变性脱发',
            '毛囊激活',
            '酒糟鼻',
            '伤口愈合清除毒素',
            '缓解痉挛抽搐',
            '减轻或缓解疾病症状',
            '处方',
            '药方',
            '临床观察具有明显效果',
            '丘疹',
            '脓疱',
            '手癣',
            '甲癣',
            '体癣',
            '头癣',
            '股癣',
            '脚癣',
            '脚气',
            '鹅掌癣',
            '花斑癣',
            '牛皮癣',
            '传染性湿疹',
            '伤风感冒',
            '经痛',
            '肌痛',
            '头痛',
            '腹痛',
            '便秘',
            '哮喘',
            '支气管炎',
            '消化不良',
            '刀伤',
            '烧伤',
            '烫伤',
            '疮痈',
            '毛囊炎',
            '皮肤感染',
            '皮肤面部痉挛',
            '细菌',
            '真菌',
            '念珠菌',
            '糠秕孢子菌',
            '厌氧菌',
            '牙孢菌',
            '痤疮',
            '毛囊寄生虫',
            '雌性激素',
            '雄性激素',
            '荷尔蒙',
            '抗生素',
            '激素',
            '药物',
            '中草药',
            '中枢神经',
            '细胞再生',
            '细胞增殖和分化',
            '免疫力',
            '患处',
            '疤痕',
            '关节痛',
            '冻疮',
            '冻伤',
            '皮肤细胞间的氧气交换',
            '红肿',
            '淋巴液',
            '毛细血管',
            '淋巴毒',
            '带来好运气',
            '增强第六感',
            '化解小人',
            '增加事业运',
            '招财进宝',
            '健康富贵',
            '提升运气',
            '有助事业',
            '护身',
            '平衡正负能量',
            '消除精神压力',
            '调和气压',
            '逢凶化吉',
            '时来运转',
            '万事亨通',
            '旺人',
            '旺财',
            '助吉避凶',
            '转富招福'
        ];
        $result = false;
        foreach ($words as $word) {
            if ( mb_stristr($title, $word) ) {
                $result = true;
                break;
            }
        }
        return $result;
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
     * @deprecated
     *
     * @return bool|float|int|mixed|string
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
     * @throws InvalidArgumentException
     *
     * @return bool
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
