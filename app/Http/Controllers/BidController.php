<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use WasteMaster\v1\Haulers\HaulerManager;
use WasteMaster\v1\Leads\LeadManager;

/**
 * Class BidController
 *
 * Used for the external bid form only.
 * 
 * @package App\Http\Controllers
 */
class BidController extends Controller
{
    /**
     * @var LeadManager
     */
    protected $leads;

    /**
     * @var HaulerManager
     */
    protected $haulers;

    public function __construct(LeadManager $leads, HaulerManager $haulers)
    {
        $this->leads = $leads;
        $this->haulers = $haulers;
    }

    /**
     * Shows the Bid form for the Hauler to bid out the job.
     *
     * @param string $code
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showForm(string $code)
    {
        $code = base64_decode($code);
        list($leadID, $haulerID) = explode('::', $code);

        $lead   = $this->leads->find($leadID);
        $hauler = $this->haulers->find($haulerID);

        return view('app.bids.form', [
            'code' => $code,
            'lead' => $lead,
            'hauler' => $hauler
        ]);
    }

}
