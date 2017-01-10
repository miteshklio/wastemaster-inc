<?php

namespace App\Listeners;

use App\Events\AcceptedBid;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailAcceptedBidder
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
     * @param  AcceptedBid  $event
     * @return void
     */
    public function handle(AcceptedBid $event)
    {
        $hauler = $event->bid->hauler;
        $lead   = $event->bid->lead;

        $data = [
            'hauler' => $hauler,
            'lead'   => $lead
        ];

        $this->mailer->send('emails.bid_accepted', $data, function ($m) use($hauler) {
            $m->subject('Your bid request from Wastemaster has been accepted')
              ->to(unserialize($hauler->emails));
        });
    }
}
