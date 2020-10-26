<?php

namespace App\Jobs\Command;

use App\Jobs\User\SendMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunCommandJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        info(sprintf('[%s][%s][开始消耗]', 'RunCommandJob', __FUNCTION__));

        $time = mt_rand(1, 10);
        info(sprintf("[%s] [%s]", __CLASS__, date("Y-m-d H:i:s")));
        dispatch((new SendMessage($time))->onQueue('user_login')->delay(now()->addSeconds($time)));
    }
}