{{--Post-Bid Price--}}
<div class="side-block @if($lead->archived) archived @endif">
    <h3>Post-Bid Matching Price</h3>

    @if (! $showPostMatchBid)
        <br>
        <p class="notice">This option will display once lower bids have been received.</p>
    @else
        <p class="amt-lg">${{ number_format($lowBid->net_monthly,2) }}</p>

        <p class="text-center">
            Submitted by
            <a href="{{ route('bids::show', ['id' => $lowBid->id]) }}">
                {{ $lowBid->hauler->name }}
            </a>
        </p>

        <br>

        @if ($isCurrentMatching)
            <p class="text-center"><b>A bid has been submitted by the current hauler.</b></p>
        @else
            <a href="{{ route('bids::postMatchRequest', ['id' => $lowBid->id]) }}" class="btn btn-primary btn-block"
               onclick="return confirm('Send bid request emails now?');" @if($lead->archived) disabled @endif>
                Send Match Request<br>to Current Hauler
            </a>
        @endif

        @if (! empty($postMatchHistory) && $postMatchHistory->count())
            <?php
            $haulers = [];
            foreach ($postMatchHistory as $item)
            {
                $haulers[] = $item->hauler->name;
            }
            ?>
            <br>
            <div class="label label-default" title="{{ implode("\n", $haulers) }}">Requested on {{ date('M j, Y g:ia', strtotime($postMatchHistory[0]->created_at)) }}</div>
        @endif
    @endif
</div>
