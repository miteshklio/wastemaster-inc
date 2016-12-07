<?php namespace App\Http\Controllers\Admin;

use WasteMaster\v1\Users\Manage;
use WasteMaster\v1\Helpers\DataTable;
use App\User;
use App\Http\Controllers\Controller;

class UserController extends Controller {

    /**
     * Dashboard
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function index()
    {
        $datatable = new DataTable(new User());

        $datatable->showColumns([
                'id'         => 'ID',
                'name'       => 'Name',
                'email'      => 'Email',
                'role_id'    => 'Role',
                'created_at' => 'Joined',
            ])
            ->searchColumns(['name', 'email'])
            ->setDefaultSort('created_at', 'desc')
            ->prepare(20);

        return view('app.admin.users.index')->with([
            'datatable' => $datatable
        ]);
    }

}
