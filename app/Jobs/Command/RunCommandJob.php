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

    public $var;

    /**
     * RunCommandJob constructor.
     *
     * @param string $handle
     */
    public function __construct($handle = 'default')
    {
        //
        $this->var = $handle;
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
        dispatch((new SendMessage($this->var))->onQueue('user:login')->delay(now()->addSeconds($time)));
    }
}
