<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //
        $this->demo('select');

    }


    public function demo($file)
    {
        $path = public_path("/../../go_script/");

        if ( !file($path . $file) ) {
            echo '文件不存在';
            info(sprintf('[%s][%s] go file is not', __CLASS__, __FUNCTION__, $file));
            exit();
        }

        echo exec("cd $path && chmod a+x $file && ./$file ");
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
        imagestring($im, 5, 190, 190, "love", $red);//输出字符串
        header('Content-type:image/gif');//通知浏览器输出的是gif图片
        imagegif($im);//输出图片
        imagedestroy($im);//销毁


    }

}
