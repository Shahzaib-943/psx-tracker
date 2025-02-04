<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FinanceType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FinanceCategory;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreEventTypeRequest;
use App\Http\Requests\UpdateEventTypeRequest;
use App\Http\Requests\StoreFinanceCategoryRequest;
use App\Http\Requests\UpdateFinanceCategoryRequest;

class FinanceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $categories = FinanceCategory::with('user');
        if ($user->isUser()) {
            $categories->where('is_common', true)
                ->orWhere('user_id', $user->id);
        }
        if ($request->ajax()) {
            $table = DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('user', function ($row) {
                    if ($row->user) {
                        $roleName = $row->user->roles->isNotEmpty() ? $row->user->roles->first()->name : 'No Role';
                        return $row->user->name . ' (' . ucfirst($roleName) . ')';
                    }
                })
                ->addColumn('type', function ($row) {
                    return $row->financeType->name;
                })
                ->addColumn('name', function ($row) {
                    return "<span style='color: {$row->color};'>{$row->name}</span>";
                })
                ->addColumn('is_common', function ($row) {
                    return $row->is_common ? 'True' : 'False';
                })
                ->addColumn('actionButton', function ($row) {
                    $editUrl = route('finance-categories.edit', $row->slug);
                    $deleteUrl = route('finance-categories.destroy', $row->id);
                    $actionButtons = '<button type="button" class="btn btn-primary btn-icon" onclick="window.location.href=\'' . $editUrl . '\'">
                    <i data-feather="edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-icon delete-button" data-url="' . $deleteUrl . '" data-type="finance category">
                        <i data-feather="trash-2"></i>
                    </button>';
                    return $actionButtons;
                })
                ->rawColumns(['actionButton', 'name']);
            if (!$user->isAdmin()) {
                $table->removeColumn('user');
                $table->removeColumn('is_common');
            }
            return $table->make(true);
        }



        return view('finance-categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::get(['id', 'name']);
        $financeTypes = FinanceType::get(['id', 'name']);
        return view('finance-categories.create', compact('users', 'financeTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFinanceCategoryRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = $validatedData['user_id'] ?? Auth::user()->id;
        $slug = Str::slug($validatedData['name'] . '-' . $validatedData['user_id']);
        if (FinanceCategory::where('slug', $slug)->exists()) {
            flash()->error('Finance category with same name already exists.');
            return redirect()->back();
        }
        $validatedData['slug'] = $slug;
        if (isset($validatedData['is_common'])) {
            $validatedData['is_common'] = true;
        }
        $financeCategoryCreated = FinanceCategory::create($validatedData);
        if ($financeCategoryCreated) {
            flash()->success('Finance Category Created Successfully.');
            return to_route('finance-categories.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FinanceCategory $financeCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinanceCategory $financeCategory)
    {
        if (!is_null($financeCategory)) {
            $users = User::get(['id', 'name']);
            $financeTypes = FinanceType::get(['id', 'name']);
            return view('finance-categories.edit', compact('financeCategory', 'financeTypes', 'users'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFinanceCategoryRequest $request, FinanceCategory $financeCategory)
    {
        $validatedData = $request->validated();
        if ($financeCategory->update($validatedData)) {
            flash()->success('Finance Category Updated Successfully.');
        } else {
            flash()->success('An unexpected error occurred. Please try again.');
        }
        return to_route('finance-categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinanceCategory $financeCategory)
    {
        if ($financeCategory->delete()) {
            return response()->json(['success' => 'Finance Category deleted successfully.']);
        } else {
            return response()->json(['error' => 'Failed to delete finance category.'], 500);
        }
    }

    public function getCategoriesByType(Request $request)
    {
        Log::info('Finance Type ID: ' . $request->finance_type_id);
        $request->validate([
            'finance_type_id' => 'required|exists:finance_types,id',
        ]);
        $categories = FinanceCategory::where('finance_type_id', $request->finance_type_id)->get();
        return response()->json($categories);
    }


}
