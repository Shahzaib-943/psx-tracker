<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\FinanceType;
use Illuminate\Http\Request;
use App\Models\FinanceRecord;
use App\Constants\AppConstant;
use App\Models\FinanceCategory;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreFinanceRecordRequest;

class FinanceRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if ($request->ajax()) {
            if ($user->hasRole(AppConstant::ROLE_ADMIN)) {
                $records = FinanceRecord::with('user:id,name', 'user.roles:id,name', 'financeCategory:id,name,finance_type_id', 'financeCategory.financeType:id,name')->get(['id', 'user_id', 'finance_category_id', 'description', 'date', 'amount']);
            } else {
                $records = FinanceRecord::with('financeCategory:id,name,finance_type_id', 'financeCategory.financeType:id,name')
                    ->where('user_id', $user->id)
                    ->get(['id', 'finance_category_id', 'description', 'date', 'amount']);
            }

            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('user', function ($row) {
                    if ($row->user) {
                        $roleName = $row->user->roles->isNotEmpty() ? $row->user->roles->first()->name : 'No Role';
                        return $row->user->name . ' (' . ucfirst($roleName) . ')';
                    }
                    return 'Admin';
                })
                ->addColumn('type', function ($row) {
                    return $row->financeCategory && $row->financeCategory->financeType
                        ? $row->financeCategory->financeType->name
                        : '-';
                })
                ->addColumn('category', function ($row) {
                    return $row->financeCategory
                        ? $row->financeCategory->name
                        : '-';
                })
                ->addColumn('date', function ($row) {
                    return Carbon::parse($row->date)->format('d M, Y');
                })
                ->addColumn('amount', function ($row) {
                    return 'Rs ' . $row->amount;
                })
                ->addColumn('description', function ($row) {
                    return $row->description ? '<span title="' . e($row->description) . '">' .
                        \Illuminate\Support\Str::limit($row->description, 15, ' ...') .
                        '</span>' : '-';
                })
                ->addColumn('actionButton', function ($row) {
                    $editUrl = route('finance-records.edit', $row->id);
                    $deleteUrl = route('finance-records.destroy', $row->id);
                    $actionButtons = '<button type="button" class="btn btn-primary btn-icon" onclick="window.location.href=\'' . $editUrl . '\'">
                <i data-feather="edit"></i>
                </button>
                <button type="button" class="btn btn-danger btn-icon delete-button" data-url="' . $deleteUrl . '" data-type="Finance Record">
                    <i data-feather="trash-2"></i>
                </button>';
                    return $actionButtons;
                })
                ->rawColumns(['actionButton', 'description'])
                ->make(true);
        }



        return view('finance-records.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::get(['id', 'name']);
        $financeTypes = FinanceType::get(['id', 'name']);
        return view('finance-records.create', compact('users', ('financeTypes')));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFinanceRecordRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = Auth::user()->id;
        $financeCategoryCreated = FinanceRecord::create($validatedData);
        if ($financeCategoryCreated) {
            flash()->success('Finance Record Created Successfully.');
            return to_route('finance-records.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FinanceRecord $financeRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinanceRecord $financeRecord)
    {
        if (!is_null($financeRecord)) {
            $users = User::get(['id', 'name']);
            $financeTypes = FinanceType::get(['id', 'name']);
            $filteredCategories = FinanceCategory::where('finance_type_id', $financeRecord->financeCategory->finance_type_id)->get(['id', 'name']);
            return view('finance-records.edit', compact('financeRecord', 'filteredCategories', 'financeTypes', 'users'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreFinanceRecordRequest $request, FinanceRecord $financeRecord)
    {
        $validatedData = $request->validated();
        if ($financeRecord->update($validatedData)) {
            flash()->success('Finance Record Updated Successfully.');
        } else {
            flash()->success('An unexpected error occurred. Please try again.');
        }
        return to_route('finance-records.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinanceRecord $financeRecord)
    {
        if ($financeRecord->delete()) {
            return response()->json(['success' => 'Finance Record deleted successfully.']);
        } else {
            return response()->json(['error' => 'Failed to delete finance record.'], 500);
        }
    }
}
