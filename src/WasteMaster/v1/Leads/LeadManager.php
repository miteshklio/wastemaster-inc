<?php namespace WasteMaster\v1\Leads;

use App\City;
use App\Lead;

class LeadManager
{
    /**
     * @var \App\Lead
     */
    protected $leads;

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
    protected $monthly_price;
    protected $status;
    protected $archived;
    protected $bid_count;
    protected $notes;

    public function __construct(Lead $leads, City $cities)
    {
        $this->leads = $leads;
        $this->cities = $cities;
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

    public function setWaste(int $qty=null, int $yards=null, int $frequency=null)
    {
        $this->msw_qty      = $qty;
        $this->msw_yards    = $yards;
        $this->msw_per_week = $frequency;

        return $this;
    }

    public function setRecycling(int $qty=null, int $yards=null, int $frequency=null)
    {
        $this->rec_qty      = $qty;
        $this->rec_yards    = $yards;
        $this->rec_per_week = $frequency;

        return $this;
    }

    public function setMonthlyPrice(int $price)
    {
        $this->monthly_price = $price;

        return $this;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }

    public function setArchived(bool $archived = true)
    {
        $this->archived = $archived;

        return $this;
    }

    public function setBidCount(int $count)
    {
        $this->bids = $count;

        return $this;
    }

    public function setNotes(string $notes)
    {
        $this->notes = $notes;

        return $this;
    }

    public function create()
    {
        $this->checkRequired();

        // Does a Lead with this address
        // already exist?
        if ($this->leads->where(['address' => $this->address, 'city_id' => $this->city_id])->count())
        {
            throw new LeadExists(trans('messages.leadExists'));
        }

        $lead = $this->leads->create([
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
            'monthly_price' => $this->monthly_price,
            'status' => Lead::NEW,
            'archived' => 0,
            'bid_count' => 0,
            'notes' => $this->notes
        ]);

        $this->reset();

        return $lead;
    }

    public function update($id)
    {
        $lead = $this->leads->find($id);

        if ($lead === null)
        {
            throw new LeadNotFound(trans('messages.leadNotFound', ['id' => $id]));
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
        if ($this->monthly_price !== null) $fields['monthly_price'] = $this->monthly_price;
        if ($this->status !== null) $fields['status'] = $this->status;
        if ($this->archived !== null) $fields['archived'] = $this->archived;
        if ($this->bid_count !== null) $fields['bid_count'] = $this->bid_count;
        if ($this->notes !== null) $fields['notes'] = $this->notes;

        if (! count($fields))
        {
            throw new NothingToUpdate(trans('messages.nothingToUpdate'));
        }

        $lead->fill($fields);
        $lead->save();

        $this->reset();

        return $lead;
    }

    public function find(int $id)
    {
        $lead = $this->leads->with(['city', 'hauler'])->find($id);

        if ($lead === null)
        {
            throw new LeadNotFound(trans('messages.leadNotFound', ['id' => $id]));
        }

        return $lead;
    }

    public function delete(int $id)
    {
        $lead = $this->find($id);

        return $lead->delete();
    }

    public function archive(int $id, bool $archived = true)
    {
        $lead = $this->find($id);

        $lead->archived = $archived;
        return $lead->save();
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
        $this->monthly_price = null;
        $this->status = null;
        $this->archived = null;
        $this->bid_count = null;
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
            'address', 'contact_name', 'contact_email', 'account_num', 'monthly_price'
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
            throw new MissingRequiredFields(trans('messages.leadValidationErrors', ['fields' => implode(', ', $errorFields)]));
        }
    }

}
