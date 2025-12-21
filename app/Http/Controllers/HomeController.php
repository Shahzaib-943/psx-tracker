<?php

namespace App\Http\Controllers;

use App\Models\FinanceCategory;
use Carbon\Carbon;
use App\Models\FinanceType;
use Illuminate\Http\Request;
use App\Models\FinanceRecord;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('dashboard');
    }
}