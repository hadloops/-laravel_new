<?php

namespace App\Jobs\User;

use App\Loop\Log;
use App\Loop\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $var;
    private $redis;

    /**
     * SendMessage constructor.
     *
     * @param $value
     */
    public function __construct($value)
    {
        //
        $this->var   = $value;
        $this->redis = new Redis();
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        info(sprintf("[%s][%s][%d][%s] ", __CLASS__, __FUNCTION__, $this->var, "开始消耗"));

        Message::sendMarkdownMsg("开始消耗", "$this->var ---->开始消耗");

        info(sprintf("[%s] [%s]", __CLASS__, date("Y-m-d H:i:s")));
        $time = mt_rand(1, 20);
        dispatch((new SendMessage($time))->onQueue('user_login')->delay(now()->addSeconds($time)));


    }
}
