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
     * Create a new job instance.
     *
     * @return void
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
        Log::error(sprintf("[%s][%d][%s] ", __CLASS__, $this->var, "开始消耗"));

        //if ( !Redis::get('biu_demo_list') ) {
        Message::sendMarkdownMsg("开始消耗", "$this->var ---->开始消耗");
        // Redis::set('biu_demo_list', 1, 100);
        //}


    }
}
