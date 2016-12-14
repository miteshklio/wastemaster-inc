<?php namespace WasteMaster\v1\Haulers;

use App\Hauler;

class Manager
{
    /**
     * @var Hauler
     */
    protected $haulers;

    protected $name;
    protected $city;
    protected $doesRecycling = false;
    protected $doesWaste     = false;
    protected $emails        = [];

    public function __construct(Hauler $hauler)
    {
        $this->haulers = $hauler;
    }

    /**
     * Sets the name to use when creating/updating a Hauler.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        
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
        $this->city = $id;
        
        return $this;
    }

    /**
     * Whether this Hauler does recycling.
     *
     * @param bool $recycles
     *
     * @return $this
     */
    public function setRecycling(bool $recycles=false)
    {
        $this->doesRecycling = (int)$recycles;
        
        return $this;
    }

    /**
     * Whether this Hauler does waste collection.
     *
     * @param bool $waste
     *
     * @return $this
     */
    public function setWaste(bool $waste = false)
    {
        $this->doesWaste = (int)$waste;
        
        return $this;
    }

    /**
     * Add one or more
     *
     * @param $emails
     *
     * @return $this
     * @internal param string $email
     */
    public function setEmails($emails)
    {
        $this->emails = $this->parseEmails($emails);

        return $this;
    }

    /**
     * Creates a new Hauler from data
     * provided by the fluent interface.
     */
    public function create()
    {
        $this->checkRequired();

        $hauler = $this->haulers->create([
            'name'        => $this->name,
            'city_id'     => $this->city,
            'svc_recycle' => (int)$this->doesRecycling,
            'svc_waste'   => (int)$this->doesWaste,
            'emails'      => serialize($this->emails)
        ]);

        return $hauler;
    }

    /**
     * Updates an existing hauler, filling in
     * properties with key/value pairs in $fields array.
     *
     * @param int   $id
     * @param array $fields
     *
     * @return
     * @throws HaulerNotFound
     */
    public function update(int $id, array $fields)
    {
        $hauler = $this->haulers->find($id);

        if ($hauler === null)
        {
            throw new HaulerNotFound(trans('messages.haulerNotFound', ['id' => $id]));
        }

        if (isset($fields['emails']))
        {
            $fields['emails'] = serialize($this->parseEmails($fields['emails']));
        }

        $hauler->fill($fields);
        $hauler->save();

        return $hauler;
    }

    /**
     * Permanently deletes an existing hauler.
     *
     * @param int $id
     *
     * @return bool|null
     */
    public function delete(int $id)
    {
        $hauler = $this->find($id);

        return $hauler->delete();
    }

    /**
     * Returns a single hauler from the database.
     *
     * @param int $id
     *
     * @return mixed
     * @throws HaulerNotFound
     */
    public function find(int $id)
    {
        $hauler = $this->haulers->find($id);

        if ($hauler === null)
        {
            throw new HaulerNotFound(trans('messages.haulerNotFound', ['id' => $id]));
        }

        return $hauler;
    }

    /**
     * Converts a comma-separated string of emails
     * into a usable array.
     *
     * @param $emails
     *
     * @return array
     */
    public function parseEmails($emails)
    {
        if (is_string($emails)) {
            $emails = explode(',', $emails);

            array_walk($emails, function (&$value) {
                $value = trim($value);
            });
        }
        else if (! is_array($emails))
        {
            throw new InvalidEmails(trans('messages.haulerInvalidEmail'));
        }

        return $emails;
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
            'name', 'city', 'emails'
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
            throw new MissingRequiredFields(trans('messages.haulerValidationErrors', ['fields' => implode(', ', $errorFields)]));
        }
    }
}
