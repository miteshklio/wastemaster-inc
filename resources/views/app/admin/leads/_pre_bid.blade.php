{{--Pre-Bid Price--}}
<div class="side-block @if($lead->archived) archived @endif">
    <h3>Pre-Bid Matching Price</h3>

    @if ($preWasteMatch !== null || $preRecycleMatch !== null)
        <p class="amt-lg">
            @if ($preWasteMatch !== null)
                ${{ number_format($preWasteMatch->net_monthly, 2) }}
            @else
                <span>MSW</span>
            @endif
            /
            @if ($preRecycleMatch !== null)
                ${{ number_format($preRecycleMatch->net_monthly, 2) }}
            @else
                <span>REC</span>
            @endif
        </p>

        @if ($preWasteMatch !== null)
            <p class="text-center"><b>Waste Services Match:</b>
                <br>by <a href="/admin/bid/{{ $preWasteMatch->id  }}">{{ $preWasteMatch->hauler->name }}</a>
                <br>for <a href="/admin/lead/{{ $preWasteMatch->lead_id }}">{{ $preWasteMatch->lead->company }}</a>
            </p>
        @endif
        @if ($preRecycleMatch !== null)
            <p class="text-center"><b>Recycling Services Match:</b>
                <br>by <a href="/admin/bid/{{ $preRecycleMatch->id }}">{{ $preRecycleMatch->hauler->name }}</a>
                <br>for <a href="/admin/lead/{{ $preRecycleMatch->lead_id }}">{{ $preRecycleMatch->lead->company}}</a>
            </p>
        @endif
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
