<?php

namespace App\Listeners;

use App\Events\RequestBidsForLead;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailBidRequests
{
    protected $mailer;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  RequestBidsForLead  $event
     * @return void
     */
    public function handle(RequestBidsForLead $event)
    {
        foreach ($event->haulers as $hauler)
        {
            $data = [
                'lead'   => $event->lead,
                'hauler' => $hauler,
                'url'    => route('bids::externalForm', ['id' => base64_encode($event->lead->id .'::'. $hauler->id)])
            ];

            $this->mailer->send('emails.general_bid', $data, function ($m) use($hauler) {
                $m->subject('A new bid request from Wastemaster')
                    ->to(unserialize($hauler->emails));
            });
        }
    }
}
