<?php

namespace App\Jobs\User;

use App\Loop\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $var;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($var)
    {
        //
        $this->var = $var;
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
    }
}
