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

        <input type="submit" class="btn btn-primary btn-block" value="Send Bid Requests"
               onclick="return confirm('Send match request to current hauler now?');" @if($lead->archived) disabled @endif>

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
