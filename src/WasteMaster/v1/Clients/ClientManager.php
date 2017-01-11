<?php namespace WasteMaster\v1\Clients;

use App\City;
use App\Client;
use App\Lead;
use Geocoder\Exception\InvalidArgument;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use WasteMaster\v1\History\HistoryManager;
use WasteMaster\v1\Leads\LeadManager;

class ClientManager
{
    /**
     * @var \App\Client
     */
    protected $clients;

    /**
     * @var \App\City
     */
    protected $cities;

    /**
     * DB Columns
     */
    protected $company;
    protected $address;
    protected $city_id;
    protected $contact_name;
    protected $contact_email;
    protected $account_num;
    protected $hauler_id;
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
    protected $gross_profit;
    protected $total;
    protected $archived;

    public function __construct(Client $clients, City $cities)
    {
        $this->clients = $clients;
        $this->cities  = $cities;
    }

    public function setCompany(string $company)
    {
        $this->company = $company;

        return $this;
    }

    public function setAddress(string $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Sets the city_id to use when creating/updating a Hauler.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setCityID(int $id)
    {
        $this->city_id = $id;

        return $this;
    }

    /**
     * Looks up the appropriate city based on the city name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setCity(string $name)
    {
        $city = $this->cities->where('name', $name)->first();

        if ($city === null)
        {
            throw new CityNotFound(trans('messages.cityNotFound'));
        }

        $this->city_id = $city->id;

        return $this;
    }

    public function setContactName(string $name)
    {
        $this->contact_name = $name;

        return $this;
    }

    public function setContactEmail(string $email)
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            throw new InvalidEmail(trans('messages.invalidEmailAddress'));
        }

        $this->contact_email = $email;

        return $this;
    }

    public function setAccountNum(string $num)
    {
        $this->account_num = $num;

        return $this;
    }

    public function setHaulerID(int $id)
    {
        $this->hauler_id = $id;

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

    public function setPriorTotal($total)
    {
        if (! is_numeric($total))
        {
            throw new InvalidArgument(trans('messages.notANumber', ['key' => 'prior_total', 'value' => $total]));
        }

        $this->prior_total = $total;

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

    public function setGross($amount)
    {
        if (! is_numeric($amount))
        {
            throw new InvalidArgument(trans('messages.notANumber', ['key' => 'gross_profit', 'value' => $amount]));
        }

        $this->gross_profit = $amount;

        return $this;
    }

    public function setTotal($amount)
    {
        if (! is_numeric($amount))
        {
            throw new InvalidArgument(trans('messages.notANumber', ['key' => 'total', 'value' => $amount]));
        }

        $this->total = $amount;

        return $this;
    }

    public function setArchived(bool $archived = true)
    {
        $this->archived = $archived;

        return $this;
    }

    public function create()
    {
        $this->checkRequired();

        // Does a Lead with this address
        // already exist?
        if ($this->clients->where(['address' => $this->address, 'city_id' => $this->city_id])->count())
        {
            throw new ClientExists(trans('messages.clientExists'));
        }

        $lead = $this->clients->create([
            'company' => $this->company,
            'address' => $this->address,
            'city_id' => $this->city_id,
            'contact_name' => $this->contact_name,
            'contact_email' => $this->contact_email,
            'account_num' => $this->account_num,
            'hauler_id' => $this->hauler_id,
            'msw_qty' => $this->msw_qty,
            'msw_yards' => $this->msw_yards,
            'msw_per_week' => $this->msw_per_week,
            'rec_qty' => $this->rec_qty,
            'rec_yards' => $this->rec_yards,
            'rec_per_week' => $this->rec_per_week,
            'prior_total' => $this->prior_total,
            'msw_price' => $this->msw_price,
            'rec_price' => $this->rec_price,
            'rec_offset' => $this->rec_offset,
            'fuel_surcharge' => $this->fuel_surcharge,
            'env_surcharge' => $this->env_surcharge,
            'recovery_fee' => $this->recovery_fee,
            'admin_fee' => $this->admin_fee,
            'other_fees' => $this->other_fees,
            'net_monthly' => $this->net_monthly,
            'gross_profit' => $this->gross_profit,
            'total' => $this->total,
            'archived' => 0,
        ]);

        $this->reset();

        return $lead;
    }

    public function update($id)
    {
        $client = $this->clients->find($id);

        if ($client === null)
        {
            throw new ClientNotFound(trans('messages.clientNotFound', ['id' => $id]));
        }

        $fields = [];

        if ($this->company !== null) $fields['company'] = $this->company;
        if ($this->address !== null) $fields['address'] = $this->address;
        if ($this->contact_name !== null) $fields['contact_name'] = $this->contact_name;
        if ($this->contact_email !== null) $fields['contact_email'] = $this->contact_email;
        if ($this->account_num !== null) $fields['account_num'] = $this->account_num;
        if ($this->hauler_id !== null) $fields['hauler_id'] = $this->hauler_id;
        if ($this->msw_qty !== null) $fields['msw_qty'] = $this->msw_qty;
        if ($this->msw_yards !== null) $fields['msw_yards'] = $this->msw_yards;
        if ($this->msw_per_week !== null) $fields['msw_per_week'] = $this->msw_per_week;
        if ($this->rec_qty !== null) $fields['rec_qty'] = $this->rec_qty;
        if ($this->rec_yards !== null) $fields['rec_yards'] = $this->rec_yards;
        if ($this->rec_per_week !== null) $fields['rec_per_week'] = $this->rec_per_week;
        if ($this->prior_total !== null) $fields['prior_total'] = $this->prior_total;
        if ($this->msw_price !== null) $fields['msw_price'] = $this->msw_price;
        if ($this->rec_price !== null) $fields['rec_price'] = $this->rec_price;
        if ($this->rec_offset !== null) $fields['rec_offset'] = $this->rec_offset;
        if ($this->fuel_surcharge !== null) $fields['fuel_surcharge'] = $this->fuel_surcharge;
        if ($this->env_surcharge !== null) $fields['env_surcharge'] = $this->env_surcharge;
        if ($this->recovery_fee !== null) $fields['recovery_fee'] = $this->recovery_fee;
        if ($this->admin_fee !== null) $fields['admin_fee'] = $this->admin_fee;
        if ($this->other_fees !== null) $fields['other_fees'] = $this->other_fees;
        if ($this->net_monthly !== null) $fields['net_monthly'] = $this->net_monthly;
        if ($this->gross_profit !== null) $fields['gross_profit'] = $this->gross_profit;
        if ($this->total !== null) $fields['total'] = $this->total;
        if ($this->archived !== null) $fields['archived'] = $this->archived;

        if (! count($fields))
        {
            throw new NothingToUpdate(trans('messages.nothingToUpdate'));
        }

        $client->fill($fields);
        $client->save();

        $this->reset();

        return $client;
    }

    public function find(int $id)
    {
        $client = $this->clients->with(['city', 'hauler'])->find($id);

        if ($client === null)
        {
            throw new ClientNotFound(trans('messages.clientNotFound', ['id' => $id]));
        }

        return $client;
    }

    public function delete(int $id)
    {
        $lead = $this->find($id);

        return $lead->delete();
    }

    public function archive(int $id, bool $archived = true)
    {
        $client = $this->find($id);

        $client->archived = $archived;
        return $client->save();
    }

    public function findOrCreate(array $params)
    {
        return $this->clients->firstOrCreate($params);
    }

    /**
     * Given a client, will set things up for a rebid
     * scenario.
     *
     * @param int $clientID
     */
    public function rebidClient(int $clientID)
    {
        $client = $this->find($clientID);
        $lead = $client->lead;

        return $this->rebid($client, $lead);
    }

    /**
     * Given a lead will set things up for a rebid
     * scenario.
     *
     * @param Lead $lead
     */
    public function rebidLead(Lead $lead)
    {
        $client = $this->clients->where('lead_id', $lead->id)->first();

        $this->rebid($client, $lead);
    }

    /**
     * Handles the actual rebid process.
     *
     * @param \App\Client $client
     * @param \App\Lead   $lead
     */
    protected function rebid(Client $client = null, Lead $lead = null)
    {
        // Un-archive the lead and fix status.
        if ($lead !== null)
        {
            $lead->archived = 0;
            $lead->status = Lead::REBIDDING;
            $lead->save();

            // Reset the History
            $history = app(HistoryManager::class);
            $history->deleteForLead($lead->id);

            // Archive the bids
            $bids = $lead->bids;

            if ($bids !== null)
            {
                foreach ($bids as $bid)
                {
                    $bid->archived = 1;
                    $bid->save();
                }
            }
        }


    }



    /**
     * Used internally after a create or udpate
     * to reset the class properties.
     */
    protected function reset()
    {
        $this->company = null;
        $this->address = null;
        $this->city_id = null;
        $this->contact_name = null;
        $this->contact_email = null;
        $this->account_num = null;
        $this->hauler_id = null;
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
        $this->gross_profit = null;
        $this->total = null;
        $this->archived = null;
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
            'address', 'contact_name', 'contact_email', 'account_num'
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
            throw new MissingRequiredFields(trans('messages.clientValidationErrors', ['fields' => implode(', ', $errorFields)]));
        }
    }

}
