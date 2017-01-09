<?php

namespace App\Listeners;

use App\Events\PreBidMatchRequest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailPreMatchRequest
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
     * @param  PreBidMatchRequest  $event
     * @return void
     */
    public function handle(PreBidMatchRequest $event)
    {
        //
    }
}
