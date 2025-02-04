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
        $user = auth()->user();
        $financeTypes = FinanceType::get(['id', 'name']);
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $financeTypeId = $request->finance_type;
        $filteredFinanceCategories = FinanceCategory::where('finance_type_id', $request->finance_type)->pluck('id')->toArray();
        $financeData = FinanceRecord::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate]) // Apply date range filter
            ->when($financeTypeId, function ($query) use ($filteredFinanceCategories) {
                return $query->whereIn('finance_category_id', $filteredFinanceCategories); // Apply finance type filter
            })
            ->selectRaw('SUM(amount) as total, finance_category_id')
            ->with('financeCategory:id,name,color')
            ->groupBy('finance_category_id')
            ->get();
        $chartData = [
            'labels' => $financeData->pluck('financeCategory.name'),
            'values' => $financeData->pluck('total'),
            'colors' => $financeData->pluck('financeCategory.color'),
        ];
        if ($request->ajax()) {
            // flash()->error('There was a problem re-verifying your account.');
            return response()->json([
                'chartData' => $chartData,
            ]);
        }
        return view('dashboard', compact('chartData', 'financeTypes'));
    }
}