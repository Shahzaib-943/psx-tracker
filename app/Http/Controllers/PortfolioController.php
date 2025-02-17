<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stock;
use App\Models\Portfolio;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StorePortfolioRequest;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $portfolios = Portfolio::query();
        if ($user->isUser()) {
            $portfolios = Portfolio::with('user')->where('user_id', $user->id);
        }
        if ($request->ajax()) {
            $table = DataTables::of($portfolios)
                ->addIndexColumn()
                ->addColumn('user', function ($row) {
                    if ($row->user) {
                        $roleName = $row->user->roles->isNotEmpty() ? $row->user->roles->first()->name : 'No Role';
                        return $row->user->name . ' (' . ucfirst($roleName) . ')';
                    }
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('description', function ($row) {
                    return Str::limit($row->description, 20, ' ...');
                })
                ->addColumn('actionButton', function ($row) {
                    $editUrl = route('portfolios.edit', $row->slug);
                    $deleteUrl = route('portfolios.destroy', $row->slug);
                    $actionButtons = '<button type="button" class="btn btn-primary btn-icon" onclick="window.location.href=\'' . $editUrl . '\'">
                    <i data-feather="edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-icon delete-button" data-url="' . $deleteUrl . '" data-type="portfolio">
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



        return view('portfolios.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::with('role')->get(['id', 'name']);
        return view('portfolios.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePortfolioRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = $validatedData['user_id'] ?? Auth::user()->id;
        $slug = Str::slug($validatedData['name'] . '-' . $validatedData['user_id']);
        if (Portfolio::where('slug', $slug)->exists()) {
            flash()->error('Portfolio with same name already exists.');
            return redirect()->back();
        }
        $validatedData['slug'] = $slug;
        $portfolioCreated = Portfolio::create($validatedData);
        if ($portfolioCreated) {
            flash()->success('Portfolio Created Successfully.');
            return to_route('portfolios.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->delete()) {
            return response()->json(['success' => 'Portfolio deleted successfully.']);
        } else {
            return response()->json(['error' => 'Failed to delete portfolio.'], 500);
        }
    }

    public function tradeStocks(Portfolio $portfolio)
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $portfolios = Portfolio::get(['name', 'slug']);
        } elseif ($user->isUSer()) {
            $portfolios = Portfolio::where('user_id', $user->id)->get(['name', 'slug']);
        }
        $stocks = Stock::get(['name', 'symbol', 'slug']);
        return view('portfolios.trade-stocks', compact('portfolios', 'stocks'));
    }
}
