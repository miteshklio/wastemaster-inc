<?php namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

class HomeController extends Controller {

    /**
     * Home page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function index()
    {
        return view('app.home');
    }

}