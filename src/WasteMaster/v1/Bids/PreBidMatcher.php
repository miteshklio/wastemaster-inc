<?php namespace WasteMaster\v1\Bids;

use App\Bid;
use App\Lead;

/**
 * Class PreBidMatcher
 *
 * Matches the lead against past bids
 * from other clients to find the lowest
 * bid that matches the requirements
 * (dumpsters, size, frequency)
 *
 * @package WasteMaster\v1\Bids
 */
class PreBidMatcher
{
    /**
     * @var Bid
     */
    protected $bids;

    public function __construct(Bid $bids)
    {
        $this->bids = $bids;
    }

    /**
     * Matches the best price on a waste service match.
     *
     * @param Lead $lead
     */
    public function matchWaste(Lead $lead)
    {
        $msw_qty = array_filter([ $lead->msw_qty, $lead->msw2_qty]);
        $msw_yards = [ $lead->msw_yards, $lead->msw2_yards];
        $msw_per_week = [$lead->msw_per_week, $lead->msw2_per_week];

        return $this->bids
            ->join('leads', 'leads.id', '=', 'bids.lead_id')
            ->where(function($query) use($msw_qty) {
                $query->whereIn('leads.msw_qty', $msw_qty)
                      ->orWhereIn('leads.msw2_qty', $msw_qty);
            })
            ->where(function($query) use($msw_yards) {
                $query->whereIn('leads.msw_yards', $msw_yards)
                      ->orWhereIn('leads.msw2_yards', $msw_yards);
            })
            ->where(function($query) use($msw_per_week) {
                $query->whereIn('leads.msw_per_week', $msw_per_week)
                      ->orWhereIn('leads.msw2_per_week', $msw_per_week);
            })
            ->select('bids.*')
            ->orderBy('net_monthly', 'asc')
            ->orderBy('bids.created_at', 'desc')
            ->where('net_monthly', '>', 0)
            ->first();
    }

    /**
     * Matches the best price on a recycling match.
     *
     * @param Lead $lead
     */
    public function matchRecycle(Lead $lead)
    {
        $rec_qty = array_filter([ $lead->rec_qty, $lead->rec2_qty]);
        $rec_yards = [ $lead->rec_yards, $lead->rec2_yards];
        $rec_per_week = [$lead->rec_per_week, $lead->rec2_per_week];

        return $this->bids
            ->join('leads', 'leads.id', '=', 'bids.lead_id')
            ->where(function($query) use($rec_qty) {
                $query->whereIn('leads.rec_qty', $rec_qty)
                      ->orWhereIn('leads.rec2_qty', $rec_qty);
            })
            ->where(function($query) use($rec_yards) {
                $query->whereIn('leads.rec_yards', $rec_yards)
                      ->orWhereIn('leads.rec2_yards', $rec_yards);
            })
            ->where(function($query) use($rec_per_week) {
                $query->whereIn('leads.rec_per_week', $rec_per_week)
                      ->orWhereIn('leads.rec2_per_week', $rec_per_week);
            })
            ->select('bids.*')
            ->orderBy('net_monthly', 'asc')
            ->orderBy('bids.created_at', 'desc')
            ->where('net_monthly', '>', 0)
            ->first();
    }
}
