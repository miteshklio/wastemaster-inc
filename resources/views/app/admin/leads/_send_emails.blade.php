<h2>Send Emails</h2>

<br>

@if (isset($lead) && $lead->created_at)

    {{--Applicable Haulers--}}
    <div class="side-block">
        <form action="{{ route('leads::sendBidRequest', ['id' => $lead->id]) }}" method="post">
            {{ csrf_field() }}

            <h3>Applicable Haulers</h3>

            @if ($cityHaulers)
                @foreach ($cityHaulers as $hauler)
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="haulers[{{ $hauler->id }}]" checked> {{ $hauler->name }}
                        </label>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    No valid Haulers are in the system.
                </div>
            @endif

            <br>

            <input type="submit" class="btn btn-primary btn-block" value="Send Bid Requests">
        </form>
    </div>

    {{--Pre-Bid Price--}}
    <div class="side-block">
        <h3>Pre-Bid Matching Price</h3>

        <p class="amt-lg">$368</p>

        <p class="text-center">Offered to <a href="#">Bob's Coffee</a>.</p>

        <br>

        <a href="#" class="btn btn-primary btn-block">
            Send Match Request<br>to Current Hauler
        </a>
    </div>

    {{--Post-Bid Price--}}
    <div class="side-block">
        <h3>Post-Bid Matching Price</h3>

        @if ($lead->bid_count == 0)
            <br>
            <p class="notice">This option will display once bids have been received.</p>
        @else
        @endif
    </div>
@else

    <p class="notice">Submit the lead information form on the left to automate bid request emails.</p>

@endif
