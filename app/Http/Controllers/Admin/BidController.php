<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Bid;
use Illuminate\Http\Request;
use WasteMaster\v1\Bids\BidManager;
use WasteMaster\v1\Haulers\HaulerManager;
use WasteMaster\v1\Bids\BidExists;
use WasteMaster\v1\Bids\BidNotFound;
use WasteMaster\v1\Helpers\DataTable;

class BidController extends Controller
{
    protected $bids;

    public function __construct(BidManager $bids)
    {
        $this->bids = $bids;
    }

    /**
     * Displays sortable table of leads in the system.
     *
     * @param Bid $model
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Bid $model)
    {
        $datatable = new DataTable($model);

        $datatable->showColumns([
            'lead_name' => 'Name',
            'status' => 'Status',
            'hauler_name' => 'Bidder',
            'prior_total' => 'Prior $',
            'created_at' => 'Submitted At',
            'prior_total' => 'Current $',
            'net_monthly' => 'Bid $'
        ])
            ->searchColumns(['company'])
            ->setDefaultSort('company', 'asc')
            ->join('leads', 'leads.id', '=', 'bids.lead_id')
            ->join('haulers', 'haulers.id', '=', 'bids.hauler_id')
            ->select('bids.*', 'leads.name as lead_name', 'leads.prior_total', 'haulers.name as hauler_name')
            ->prepare(20);

        return view('app.admin.bids.index')->with([
            'datatable' => $datatable
        ]);
    }

    /**
     * Displays the create Lead form.
     */
    public function newBid(HaulerManager $haulers)
    {
        return view('app.admin.bids.form', [
            'editMode' => false,
            'haulers' => $haulers->all()
        ]);
    }

    /**
     * Actual Lead creation.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'company' => 'required|max:255',
            'address' => 'required',
            'city' => 'required',
            'contact_name' => 'required|max:255',
            'contact_email' => 'required|email|max:255',
            'account_num' => 'required|max:255',
            'hauler_id' => 'required|integer',
            'msw_qty' => 'integer',
            'msw_yards' => 'integer',
            'msw_per_week' => 'integer',
            'rec_qty' => 'integer',
            'rec_yards' => 'integer',
            'rec_per_week' => 'integer',
            'prior_total' => 'numeric',
            'msw_price' => 'numeric',
            'rec_price' => 'numeric',
            'rec_offset' => 'numeric',
            'fuel_surcharge' => 'numeric',
            'env_surcharge' => 'numeric',
            'recovery_fee' => 'numeric',
            'admin_fee' => 'numeric',
            'other_fees' => 'numeric',
            'net_monthly' => 'numeric',
            'gross_profit' => 'numeric',
            'total' => 'numeric',
        ]);

        try
        {
            $this->bids
                ->setCompany($request->input('company'))
                ->setAddress($request->input('address'))
                ->setCity($request->input('city'))
                ->setContactName($request->input('contact_name'))
                ->setContactEmail($request->input('contact_email'))
                ->setAccountNum($request->input('account_num'))
                ->setHaulerID($request->input('hauler_id'))
                ->setWaste(
                    $request->input('msw_qty'),
                    $request->input('msw_yards'),
                    $request->input('msw_per_week')
                )
                ->setRecycling(
                    $request->input('rec_qty'),
                    $request->input('rec_yards'),
                    $request->input('rec_per_week')
                )
                ->setPriorTotal($request->input('prior_total'))
                ->setWastePrice($request->input('msw_price'))
                ->setRecyclePrice($request->input('rec_price'))
                ->setRecycleOffset($request->input('rec_offset'))
                ->setFuelSurcharge($request->input('fuel_surcharge'))
                ->setEnvironmentalSurcharge($request->input('env_surcharge'))
                ->setRecoveryFee($request->input('recovery_fee'))
                ->setAdminFee($request->input('admin_fee'))
                ->setOtherFees($request->input('other_fees'))
                ->setNet($request->input('net_monthly'))
                ->setGross($request->input('gross_profit'))
                ->setTotal($request->input('total'))
                ->create();

            return redirect()->route('bids::home')->with(['message' => trans('messages.bidCreated')]);
        } catch(BidExists $e)
        {
            return redirect()->back()->with(['message' => $e->getMessage()]);
        }
    }

    /**
     * Displays the edit a Lead form.
     *
     * @param HaulerManager $haulers
     * @param int           $bidID
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(HaulerManager $haulers, int $bidID)
    {
        $bid = $this->bids->find($bidID);

        if ($bid === null)
        {
            return redirect()->back()->with(['message' => trans('messages.bidNotFound')]);
        }

        $cityHaulers = $haulers->inCity($bid->city_id);

        return view('app.admin.bids.form', [
            'bid' => $bid,
            'editMode' => true,
            'haulers' => $haulers->all(),
            'cityHaulers' => $cityHaulers,
        ]);
    }

    /**
     * Handles actually saving the updated record.
     *
     * @param Request $request
     * @param int     $bidID
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $bidID)
    {
        $this->validate($request, [
            'company' => 'required|max:255',
            'address' => 'required',
            'city' => 'required',
            'contact_name' => 'required|max:255',
            'contact_email' => 'required|email|max:255',
            'account_num' => 'required|max:255',
            'hauler_id' => 'required|integer',
            'msw_qty' => 'integer',
            'msw_yards' => 'integer',
            'msw_per_week' => 'integer',
            'rec_qty' => 'integer',
            'rec_yards' => 'integer',
            'rec_per_week' => 'integer',
            'prior_total' => 'numeric',
            'msw_price' => 'numeric',
            'rec_price' => 'numeric',
            'rec_offset' => 'numeric',
            'fuel_surcharge' => 'numeric',
            'env_surcharge' => 'numeric',
            'recovery_fee' => 'numeric',
            'admin_fee' => 'numeric',
            'other_fees' => 'numeric',
            'net_monthly' => 'numeric',
            'gross_profit' => 'numeric',
            'total' => 'numeric',
        ]);

        try
        {
            $this->bids
                ->setCompany($request->input('company'))
                ->setAddress($request->input('address'))
                ->setCity($request->input('city'))
                ->setContactName($request->input('contact_name'))
                ->setContactEmail($request->input('contact_email'))
                ->setAccountNum($request->input('account_num'))
                ->setHaulerID($request->input('hauler_id'))
                ->setWaste(
                    $request->input('msw_qty'),
                    $request->input('msw_yards'),
                    $request->input('msw_per_week')
                )
                ->setRecycling(
                    $request->input('rec_qty'),
                    $request->input('rec_yards'),
                    $request->input('rec_per_week')
                )
                ->setPriorTotal($request->input('prior_total'))
                ->setWastePrice($request->input('msw_price'))
                ->setRecyclePrice($request->input('rec_price'))
                ->setRecycleOffset($request->input('rec_offset'))
                ->setFuelSurcharge($request->input('fuel_surcharge'))
                ->setEnvironmentalSurcharge($request->input('env_surcharge'))
                ->setRecoveryFee($request->input('recovery_fee'))
                ->setAdminFee($request->input('admin_fee'))
                ->setOtherFees($request->input('other_fees'))
                ->setNet($request->input('net_monthly'))
                ->setGross($request->input('gross_profit'))
                ->setTotal($request->input('total'))
                ->update($bidID);

            return redirect()->route('bids::show', ['id' => $bidID])->with(['message' => trans('messages.bidUpdated')]);
        } catch(BidExists $e)
        {
            return redirect()->back()->with(['message' => $e->getMessage()]);
        }
    }

    /**
     * Deletes a Lead.
     *
     * @param int $bidID
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(int $bidID)
    {
        try {
            $this->bids->delete($bidID);

            return redirect()->route('bids::home')->with(['message' => trans('messages.bidDeleted')]);
        }
        catch (BidNotFound $e)
        {
            return redirect()->back()->with(['message' => $e->getMessage()]);
        }
    }

    /**
     * Sets the archive flag on a lead.
     *
     * @param int $bidID
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function archive(int $bidID)
    {
        try {
            $this->bids->archive($bidID);

            return redirect()->route('bids::home')->with(['message' => trans('messages.bidArchived')]);
        }
        catch (BidNotFound $e)
        {
            return redirect()->back()->with(['message' => $e->getMessage()]);
        }
    }

    /**
     * Unarchives a Lead
     *
     * @param int $bidID
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unarchive(int $bidID)
    {
        try {
            $this->bids->archive($bidID, false);

            return redirect()->route('bids::home')->with(['message' => trans('messages.bidUnArchived')]);
        }
        catch (BidNotFound $e)
        {
            return redirect()->back()->with(['message' => $e->getMessage()]);
        }
    }

    /**
     * Rebids a bid. Used when the bid
     *
     * @param int $bidID
     *
     * @return string
     */
    public function rebid(int $bidID)
    {
        return '<h1>Coming Soon</h1>';
    }


}
