{{--Pre-Bid Price--}}
<div class="side-block @if($lead->archived) archived @endif">
    <h3>Pre-Bid Matching Price</h3>

    @if ($preMatchBid !== null)
        <p class="amt-lg">${{ number_format($preMatchBid->net_monthly, 2) }}</p>
        <p class="text-center">by {{ $preMatchBid->hauler->name }} 
            <br>on <a href="/admin/bid/{{ $preMatchBid->id }}">{{ $preMatchBid->created_at->format('F j, Y') }}</a>
            <br>for <a href="/admin/lead/{{ $preMatchBid->lead_id }}">{{ $preMatchBid->lead->company }}</a>
        </p>
    @else
        <p>Not in system.</p>
    @endif

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
