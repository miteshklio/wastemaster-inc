<?php namespace WasteMaster\v1\Bids;

use App\Bid;
use App\City;
use App\Client;
use Geocoder\Exception\InvalidArgument;

class BidManager
{
    /**
     * @var \App\Bid
     */
    protected $bids;

    /**
     * DB Columns
     */
    protected $hauler_id;
    protected $hauler_email;
    protected $lead_id;
    protected $status;
    protected $notes;
    protected $msw_qty;
    protected $msw_yards;
    protected $msw_per_week;
    protected $rec_qty;
    protected $rec_yards;
    protected $rec_per_week;
    protected $prior_total;
    protected $msw_price;
    protected $rec_price;
    protected $rec_offset;
    protected $fuel_surcharge;
    protected $env_surcharge;
    protected $recovery_fee;
    protected $admin_fee;
    protected $other_fees;
    protected $net_monthly;

    public function __construct(Bid $bids)
    {
        $this->bids = $bids;
    }


    public function setHaulerID(int $id)
    {
        $this->hauler_id = $id;

        return $this;
    }

    public function setHaulerEmail(string $email)
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            throw new InvalidEmail(trans('messages.invalidEmailAddress', ['email' => $email]));
        }

        $this->hauler_email = $email;

        return $this;
    }

    public function setLeadID(int $leadID)
    {
        $this->lead_id = $leadID;

        return $this;
    }

    public function setStatus(int $statusID)
    {
        $validStatus = [
            Bid::STATUS_LIVE,
            Bid::STATUS_ACCEPTED,
            Bid::STATUS_CLOSED,
        ];

        if (! in_array($statusID, $validStatus))
        {
            throw new InvalidStatus(trans('messages.bidInvalidStatus', ['status' => $statusID]));
        }

        $this->status = $statusID;

        return $this;
    }

    public function setNotes(string $notes)
    {
        $this->notes = $notes;

        return $this;
    }


    public function setWaste(int $qty, int $yards, int $frequency)
    {
        $this->msw_qty      = $qty;
        $this->msw_yards    = $yards;
        $this->msw_per_week = $frequency;

        return $this;
    }

    public function setRecycling(int $qty, int $yards, int $frequency)
    {
        $this->rec_qty      = $qty;
        $this->rec_yards    = $yards;
        $this->rec_per_week = $frequency;

        return $this;
    }

    public function setWastePrice($price)
    {
        if (! is_numeric($price))
        {
            throw new InvalidArgument(trans('messages.notANumber', ['key' => 'msw_price', 'value' => $price]));
        }

        $this->msw_price = $price;

        return $this;
    }

    public function setRecyclePrice($price)
    {
        if (! is_numeric($price))
        {
            throw new InvalidArgument(trans('messages.notANumber', ['key' => 'rec_price', 'value' => $price]));
        }

        $this->rec_price = $price;

        return $this;
    }

    public function setRecycleOffset($amount)
    {
        if (! is_numeric($amount))
        {
            throw new InvalidArgument(trans('messages.notANumber', ['key' => 'rec_offset', 'value' => $amount]));
        }

        $this->rec_offset = $amount;

        return $this;
    }

    public function setFuelSurcharge($amount)
    {
        if (! is_numeric($amount))
        {
            throw new InvalidArgument(trans('messages.notANumber', ['key' => 'fuel_surcharge', 'value' => $amount]));
        }

        $this->fuel_surcharge = $amount;

        return $this;
    }

    public function setEnvironmentalSurcharge($amount)
    {
        if (! is_numeric($amount))
        {
            throw new InvalidArgument(trans('messages.notANumber', ['key' => 'env_surcharge', 'value' => $amount]));
        }

        $this->env_surcharge = $amount;

        return $this;
    }

    public function setRecoveryFee($fee)
    {
        if (! is_numeric($fee))
        {
            throw new InvalidArgument(trans('messages.notANumber', ['key' => 'recovery_fee', 'value' => $fee]));
        }

        $this->recovery_fee = $fee;

        return $this;
    }

    public function setAdminFee($fee)
    {
        if (! is_numeric($fee))
        {
            throw new InvalidArgument(trans('messages.notANumber', ['key' => 'admin_fee', 'value' => $fee]));
        }

        $this->admin_fee = $fee;

        return $this;
    }

    public function setOtherFees($fee)
    {
        if (! is_numeric($fee))
        {
            throw new InvalidArgument(trans('messages.notANumber', ['key' => 'other_fees', 'value' => $fee]));
        }

        $this->other_fees = $fee;

        return $this;
    }

    public function setNet($amount)
    {
        if (! is_numeric($amount))
        {
            throw new InvalidArgument(trans('messages.notANumber', ['key' => 'net_monthly', 'value' => $amount]));
        }

        $this->net_monthly = $amount;

        return $this;
    }


    public function create()
    {
        $this->checkRequired();

        // Does a Lead with this address
        // already exist?
        if ($this->bids->where(['lead_id' => $this->lead_id, 'hauler_id' => $this->hauler_id])->count())
        {
            throw new BidExists(trans('messages.bidExists'));
        }

        $lead = $this->bids->create([
            'hauler_id' => $this->hauler_id,
            'hauler_email' => $this->hauler_email,
            'lead_id' => $this->lead_id,
            'status' => $this->status,
            'notes' => $this->notes,
            'msw_price' => $this->msw_price,
            'rec_price' => $this->rec_price,
            'rec_offset' => $this->rec_offset,
            'fuel_surcharge' => $this->fuel_surcharge,
            'env_surcharge' => $this->env_surcharge,
            'recovery_fee' => $this->recovery_fee,
            'admin_fee' => $this->admin_fee,
            'other_fees' => $this->other_fees,
            'net_monthly' => $this->net_monthly,
        ]);

        $this->reset();

        return $lead;
    }

    public function update($id)
    {
        $bid = $this->bids->find($id);

        if ($bid === null)
        {
            throw new BidNotFound(trans('messages.bidNotFound', ['id' => $id]));
        }

        $fields = [];


        if ($this->hauler_id !== null) $fields['hauler_id'] = $this->hauler_id;
        if ($this->hauler_email !== null) $fields['hauler_email'] = $this->hauler_email;
        if ($this->lead_id !== null) $fields['lead_id'] = $this->lead_id;
        if ($this->status !== null) $fields['status'] = $this->status;
        if ($this->notes !== null) $fields['notes'] = $this->notes;
        if ($this->msw_price !== null) $fields['msw_price'] = $this->msw_price;
        if ($this->rec_price !== null) $fields['rec_price'] = $this->rec_price;
        if ($this->rec_offset !== null) $fields['rec_offset'] = $this->rec_offset;
        if ($this->fuel_surcharge !== null) $fields['fuel_surcharge'] = $this->fuel_surcharge;
        if ($this->env_surcharge !== null) $fields['env_surcharge'] = $this->env_surcharge;
        if ($this->recovery_fee !== null) $fields['recovery_fee'] = $this->recovery_fee;
        if ($this->admin_fee !== null) $fields['admin_fee'] = $this->admin_fee;
        if ($this->other_fees !== null) $fields['other_fees'] = $this->other_fees;
        if ($this->net_monthly !== null) $fields['net_monthly'] = $this->net_monthly;

        if (! count($fields))
        {
            throw new NothingToUpdate(trans('messages.nothingToUpdate'));
        }

        $bid->fill($fields);
        $bid->save();

        $this->reset();

        return $bid;
    }

    public function find(int $id)
    {
        $bid = $this->bids->with(['lead', 'hauler'])->find($id);

        if ($bid === null)
        {
            throw new BidNotFound(trans('messages.bidNotFound', ['id' => $id]));
        }

        return $bid;
    }

    public function findExisting(int $leadID, int $haulerID)
    {
        $bid = $this->bids->with(['lead', 'hauler'])
            ->where('lead_id', $leadID)
            ->where('hauler_id', $haulerID)
            ->first();

        if ($bid === null)
        {
            throw new BidNotFound(trans('messages.bidsNotFound'));
        }

        return $bid;
    }

    public function delete(int $id)
    {
        $bid = $this->find($id);

        return $bid->delete();
    }

    /**
     * Accepts this bid and closes all other bids
     * for the same lead.
     *
     * @param int $bidID
     *
     * @return $this
     */
    public function acceptBid(int $bidID)
    {
        $bid = $this->find($bidID);

        // Close all bids for this lead
        $this->bids
            ->where('lead_id', $bid->lead_id)
            ->update(['status' => Bid::STATUS_CLOSED]);

        // Set this bid to accepted
        $bid->status = Bid::STATUS_ACCEPTED;
        $bid->save();

        return $this;
    }

    /**
     * Resets all bids for this lead to be Live.
     *
     * @param int $bidID
     *
     * @return $this
     */
    public function rescindBid(int $bidID)
    {
        $bid = $this->find($bidID);

        // Close all bids for this lead
        $this->bids->where('lead_id', $bid->lead_id)
            ->update(['status' => Bid::STATUS_LIVE]);

        return $this;
    }


    /**
     * Used internally after a create or udpate
     * to reset the class properties.
     */
    protected function reset()
    {
        $this->hauler_id = null;
        $this->hauler_email = null;
        $this->lead_id = null;
        $this->status = null;
        $this->notes = null;
        $this->msw_qty = null;
        $this->msw_yards = null;
        $this->msw_per_week = null;
        $this->rec_qty = null;
        $this->rec_per_week = null;
        $this->rec_yards = null;
        $this->prior_total = null;
        $this->msw_price = null;
        $this->rec_price = null;
        $this->fuel_surcharge = null;
        $this->env_surcharge = null;
        $this->recovery_fee = null;
        $this->admin_fee = null;
        $this->other_fees = null;
        $this->net_monthly = null;
    }

    /**
     * Checks that we have the fields we need to create
     * a new Hauler, or throws a validation error.
     */
    protected function checkRequired()
    {
        // doesWaste and doesRecycling will return
        // false alarm when a '0'.
        $requiredFields = [
            'hauler_id', 'hauler_email', 'lead_id', 'status', 'msw_price', 'rec_price', 'net_monthly'
        ];

        $errorFields = [];

        foreach ($requiredFields as $field)
        {
            if (empty($this->$field))
            {
                $errorFields[] = $field;
            }
        }

        if (count($errorFields))
        {
            throw new MissingRequiredFields(trans('messages.bidValidationErrors', ['fields' => implode(', ', $errorFields)]));
        }
    }

}