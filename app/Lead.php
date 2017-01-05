<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'monthly_price', 'status', 'archived', 'bid_count'
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
        return 'N/A';
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

}
