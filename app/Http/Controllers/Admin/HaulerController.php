<?php namespace App\Http\Controllers\Admin;

use App\Hauler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use WasteMaster\v1\Haulers\HaulerManager;
use WasteMaster\v1\Helpers\DataTable;

class HaulerController extends Controller
{
    protected $haulers;

    public function __construct(HaulerManager $haulers)
    {
        $this->haulers     = $haulers;
    }

    /**
     * Displays sortable table of haulers in the system.
     *
     * @param Hauler $model
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Hauler $model)
    {
        $datatable = new DataTable($model);

        $datatable->showColumns([
            'name'       => 'Name',
            'city_id'    => 'Operating Area',
            'svc_waste'  => 'Waste Types',
            'emails'     => 'Associated Emails',
        ])
            ->searchColumns(['name', 'emails', 'city_id'])
            ->setDefaultSort('name', 'asc')
            ->prepare(20);

        return view('app.admin.haulers.index')->with([
            'datatable' => $datatable
        ]);
    }

    /**
     * Displays the create Hauler form.
     */
    public function newHauler()
    {
        return view('app.admin.haulers.form');
    }

    /**
     * Actual Hauler creation.
     *
     * @param Request $request
     */
    public function create(Request $request)
    {

    }

    /**
     * Displays the edit a Hauler form.
     *
     * @param int $haulerID
     */
    public function show(int $haulerID)
    {

    }

    /**
     * Handles actually saving the udpated record.
     *
     * @param Request $request
     * @param int     $haulerID
     */
    public function update(Request $request, int $haulerID)
    {

    }

    /**
     * Deletes a Hauler.
     *
     * @param Request $request
     * @param int     $haulerID
     */
    public function delete(Request $request, int $haulerID)
    {

    }

}
