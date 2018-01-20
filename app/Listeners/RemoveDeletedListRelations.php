<?php

namespace App\Listeners;

use App\Events\ListDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveDeletedListRelations
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
     * @param  ListDeleted  $event
     * @return void
     */
    public function handle(ListDeleted $event)
    {
        $event->list->users->each( function($user) use ($event) {
            $user->leaveList($event->list);
        });
    }
}
