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
     * Examines the given lead and returns
     * the lowest price bid or null.
     *
     * @param Lead $lead
     *
     * @return Bid|null
     */
    public function matchFor(Lead $lead)
    {
        $this->prepare($lead);

        return $this->bids
            ->select('bids.*')
            ->orderBy('net_monthly', 'asc')
            ->orderBy('bids.created_at', 'desc')
            ->first();
    }

    /**
     * Parses the lead and sets up the
     * conditionals on the query.
     *
     * @param Lead $lead
     */
    protected function prepare(Lead $lead)
    {
        $msw_qty = [ $lead->msw_qty, $lead->msw2_qty];
        $msw_yards = [ $lead->msw_yards, $lead->msw2_yards];
        $msw_per_week = [$lead->msw_per_week, $lead->msw2_per_week];

        $rec_qty = [ $lead->rec_qty, $lead->rec2_qty];
        $rec_yards = [ $lead->rec_yards, $lead->rec2_yards];
        $rec_per_week = [$lead->rec_per_week, $lead->rec2_per_week];

        // Need to join in the leads table to know about dumpsters.
        $this->bids = $this->bids
            ->join('leads', 'leads.id', '=', 'bids.lead_id')
            // Waste
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
            // Recycling
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
            });
    }
}
