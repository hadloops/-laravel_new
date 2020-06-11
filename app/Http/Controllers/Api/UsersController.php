<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Loop\Tools;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    //

    public function rpush()
    {
        $redis = app('redis');

        $redis->rpush('wx-test', ...['1', 1,2,2,1,2,3,4,5,5,2,6,7,8,6,5,4,3,3,2,3,3,3,3,33,3,3]);
    }


    /**
     *  模板下载
     * @throws \PHPExcel_Writer_Exception
     */
    public function export()
    {
        $dataSet = [
            ['商品ID', '秒杀价格', '秒杀库存', '默认消耗', '限购数量', '是否为新会员专享', '排序'],
            ['15001028', '19.99', '58', '12', '1', '是', '4'],
        ];
        $sheetTitle = '会员权益秒杀商品模板';
        $objWriter = Tools::genExcelWriterByArr($dataSet, $sheetTitle);
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $sheetTitle . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        // header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter->save('php://output');
        exit;
    }
}
