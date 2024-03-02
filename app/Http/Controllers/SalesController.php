<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SalesController extends Controller
{
    /**
     * Show the page to record sales
     */
    public function index(): View
    {
        return view('coffee_sales');
    }

}
