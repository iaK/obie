<?php

namespace App\Listeners;

use App\Jobs\Say;
use App\Events\ListJoined;
use BotMan\Drivers\Web\WebDriver;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InstructAboutJoinedList
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ListJoined $event)
    {
        if ($event->lists->count() > 1) {

            //Say::dispatch($event->bot, "Nu är du ansluten till fler listor. För att välja vilken lista som ska vara aktiv - skriv \"använd [lista]\". Du kan bara ha en aktiv lista itaget :)", auth()->id());
        }
    }
}
