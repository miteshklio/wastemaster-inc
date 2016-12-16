<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lead;
use Illuminate\Http\Request;
use App\Http\Requests;

class LeadsController extends Controller
{
    protected $leads;

    public function __construct(Lead $leads)
    {
        $this->leads = $leads;
    }

}
