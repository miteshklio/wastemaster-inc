<?php

namespace App\Listeners;

use App\Events\PostBidMatchRequest;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailPostMatchRequest
{
    /**
     * @var \Illuminate\Contracts\Mail\Mailer
     */
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
     * @param  PostBidMatchRequest  $event
     * @return void
     */
    public function handle(PostBidMatchRequest $event)
    {
        $lead = $event->bid->lead;
        $hauler = $lead->hauler;

        $data = [
            'bid'    => $event->bid,
            'lead'   => $lead,
            'hauler' => $hauler,
            'form_url' => route('bids::externalForm', ['id' => base64_encode($lead->id .'::'. $hauler->id)])
        ];

        $this->mailer->send('emails.post_match_request', $data, function ($m) use($hauler) {
            $m->subject('A new bid request from Wastemaster')
              ->to(unserialize($hauler->emails));
        });

        $lead->post_match_sent = date('Y-m-d H:i:s');
        $lead->save();
    }
}
