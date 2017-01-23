<?php namespace WasteMaster\v1\ServiceAreas;

use App\ServiceArea;

class ServiceAreaManager {

    protected $areas;

    protected $name;

    public function __construct(ServiceArea $areas)
    {
        $this->areas = $areas;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function create()
    {
        if (empty($this->name))
        {
            throw new
        }
    }


}
