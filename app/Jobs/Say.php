<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Say implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bot;
    protected $message;
    protected $to;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($bot, $message, $to)
    {
        $this->bot = $bot;
        $this->message = $message;
        $this->to = $to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->bot->say($this->message, $this->to);
    }
}
