<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use WasteMaster\v1\Bids\BidManager;

class Lead extends Model
{
    use SoftDeletes;

    // Status constants
    const NEW                 = 1;
    const REBIDDING           = 2;
    const BIDS_REQUESTED      = 3;
    const BID_ACCEPTED        = 4;
    const CONVERTED_TO_CLIENT = 5;

    protected $table = 'leads';

    public $fillable = [
        'company', 'address', 'city_id', 'contact_name', 'contact_email', 'account_num',
        'hauler_id', 'msw_qty', 'msw_yards', 'msw_per_week', 'rec_qty', 'rec_yards', 'rec_per_week',
        'monthly_price', 'status', 'archived', 'bid_count', 'notes'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The City/State combo the lead is in.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function city()
    {
        return $this->hasOne('App\City', 'id', 'city_id');
    }

    /**
     * The current Hauler they have when the lead is created.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hauler()
    {
        return $this->hasOne('App\Hauler', 'id', 'hauler_id');
    }

    /**
     * Increments the bid count by one.
     * We keep the count separate here because count() in MySQL
     * can be slow and, at times, inaccurate.
     *
     * @return $this
     */
    public function incrementBidCount()
    {
        $this->bid_count++;

        return $this;
    }

    /**
     * Displays the cheapest bid amount this lead has received so far,
     * or 'N/A' if none.
     *
     * if $format is true, will display with currency symbol.
     *
     * @param bool $format
     *
     * @return string
     */
    public function cheapestBid($format = false)
    {
        $bids = app(BidManager::class);

        $bid = $bids->cheapestForLead($this->id);

        return $bid === null
            ? 'N/A'
            : '$'. number_format($bid->net_monthly, 2);
    }

    /**
     * Returns the number of bids currently in the system for the current lead.
     *
     * @return mixed
     */
    public function bidCount()
    {
        return \DB::table('bids')->where('lead_id', $this->id)->count();
    }

    public function status()
    {
        switch ($this->status)
        {
            case self::NEW:
                return 'New';
                break;
            case self::REBIDDING:
                return 'Re-Bidding';
                break;
            case self::BIDS_REQUESTED:
                return 'Bids Requested';
                break;
            case self::BID_ACCEPTED:
                return 'Bid Accepted';
                break;
            case self::CONVERTED_TO_CLIENT:
                return 'Converted to Client';
                break;
        }

        return 'New';
    }


}
