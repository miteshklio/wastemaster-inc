<h2>Send Emails</h2>

<br>

@if (isset($lead) && $lead->created_at)

    {{--Applicable Haulers--}}
    <div class="side-block @if($lead->archived) archived @endif">
        <form action="{{ route('leads::sendBidRequest', ['id' => $lead->id]) }}" method="post">
            {{ csrf_field() }}

            <h3>Applicable Haulers</h3>

            @if ($cityHaulers && $cityHaulers->count() > 0)
                @foreach ($cityHaulers as $hauler)
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="haulers[{{ $hauler->id }}]" checked> {{ $hauler->name }}
                        </label>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    No additional Haulers found that match this Lead's needs.
                </div>
            @endif

            <br>

            <input type="submit" class="btn btn-primary btn-block" value="Send Bid Requests" @if($lead->archived) disabled @endif>

            @if (! empty($bidRequestHistory) && $bidRequestHistory->count())
                <?php
                    $haulers = [];
                    foreach ($bidRequestHistory as $item)
                    {
                        if (empty($item)) continue;

                        $h = $item->hauler;
                        if (empty($h)) continue;

                        $haulers[] = $h->name;
                    }
                ?>
                <br>
                <div class="label label-default" title="{{ implode("\n", $haulers) }}">Requested on {{ date('M j, Y g:ia', strtotime($bidRequestHistory[0]->created_at)) }}</div>
            @endif
        </form>
    </div>

    {{--Pre-Bid Price--}}
    <div class="side-block @if($lead->archived) archived @endif">
        <h3>Pre-Bid Matching Price</h3>

        {{--<p class="amt-lg">$368</p>--}}

        <p class="text-center">This feature will display once more information has been added to the database.</p>

        <br>

        {{--<a href="#" class="btn btn-primary btn-block">--}}
            {{--Send Match Request<br>to Current Hauler--}}
        {{--</a>--}}

        @if (! empty($preMatchHistory) && $preMatchHistory->count())
            <?php
            $haulers = [];
            foreach ($preMatchHistory as $item)
            {
                $haulers[] = $item->hauler->name;
            }
            ?>
            <br>
            <div class="label label-default" title="{{ implode("\n", $haulers) }}">Requested on {{ date('M j, Y g:ia', strtotime($preMatchHistory[0]->created_at)) }}</div>
        @endif
    </div>

    {{--Post-Bid Price--}}
    <div class="side-block @if($lead->archived) archived @endif">
        <h3>Post-Bid Matching Price</h3>

        @if ($lowBid === null)
            <br>
            <p class="notice">This option will display once bids have been received.</p>
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
                <a href="{{ route('bids::postMatchRequest', ['id' => $lowBid->id]) }}" class="btn btn-primary btn-block" @if($lead->archived) disabled @endif>
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
@else

    <p class="notice">Submit the lead information form on the left to automate bid request emails.</p>

@endif


