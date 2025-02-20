<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stock;
use App\Models\Portfolio;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\StockTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StorePortfolioRequest;
use App\Models\SystemSettings;

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
                    $portfolioUrl = route('portfolios.show', $row->slug);
                    $actionButton = '<a  type="button" onclick="window.location.href=\'' . $portfolioUrl . '\'">
                    ' . $row->name . '
                    </a>';
                    return $actionButton;

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
    public function show(Portfolio $portfolio)
    {
        // $user = auth()->user();
        // $portfolio->load('holdings:id,portfolio_id,stock_id,quantity,average_cost,total_investment','holdings.stock:id,symbol');
        // $portfolioHoldings = $portfolio->holdings->toArray();
        // dd("portfolioHoldings : ", $portfolioHoldings);
        // $portfolios = Portfolio::query();
        // if ($user->isUser()) {
        //     $portfolios = Portfolio::with('user')->where('user_id', $user->id);
        // }
        // if ($request->ajax()) {
        //     $table = DataTables::of($portfolios)
        //         ->addIndexColumn()
        //         ->addColumn('user', function ($row) {
        //             if ($row->user) {
        //                 $roleName = $row->user->roles->isNotEmpty() ? $row->user->roles->first()->name : 'No Role';
        //                 return $row->user->name . ' (' . ucfirst($roleName) . ')';
        //             }
        //         })
        //         ->addColumn('name', function ($row) {
        //             $portfolioUrl = route('portfolios.show', $row->slug);
        //             $actionButton = '<a  type="button" onclick="window.location.href=\'' . $portfolioUrl . '\'">
        //             ' . $row->name . '
        //             </a>';
        //             return $actionButton;

        //         })
        //         ->addColumn('description', function ($row) {
        //             return Str::limit($row->description, 20, ' ...');
        //         })
        //         ->addColumn('actionButton', function ($row) {
        //             $editUrl = route('portfolios.edit', $row->slug);
        //             $deleteUrl = route('portfolios.destroy', $row->slug);
        //             $actionButtons = '<button type="button" class="btn btn-primary btn-icon" onclick="window.location.href=\'' . $editUrl . '\'">
        //             <i data-feather="edit"></i>
        //             </button>
        //             <button type="button" class="btn btn-danger btn-icon delete-button" data-url="' . $deleteUrl . '" data-type="portfolio">
        //                 <i data-feather="trash-2"></i>
        //             </button>';
        //             return $actionButtons;
        //         })
        //         ->rawColumns(['actionButton', 'name']);
        //     if (!$user->isAdmin()) {
        //         $table->removeColumn('user');
        //         $table->removeColumn('is_common');
        //     }
        //     return $table->make(true);
        // }
// Fetch holdings with related stocks
        $holdings = $portfolio->holdings()->with('stock')->get();
// dd("holdings", $holdings);
        // Calculate investment amount
        $investmentAmount = $holdings->sum('total_investment');
// dd("a");
// dd("aaa", $investmentAmount);
        $stockPrices = $this->getStockPrices($holdings);
        // Unrealized Profit (Market Value - Investment)
        $marketValue = $holdings->sum(fn($holding) => ($stockPrices[$holding->stock->symbol] ?? 0) * $holding->quantity);
// dd("market value : ", $marketValue);
// Unrealized Profit
        $unrealizedProfit = round($marketValue - $investmentAmount,2);

        // dd("investmentAmount", $prices);

        // Realized Profit from transactions table (sum of sell transactions)
        $realizedProfit = StockTransaction::where('portfolio_id', $portfolio->id)
            ->where('transaction_type', 'sell')
            ->sum('net_amount');

        // Today's Return (change in stock price * quantity)
        $todaysReturn = $holdings->sum(fn($holding) => ($holding->stock->current_price - $holding->stock->previous_close) * $holding->quantity);

        // Total Return %
        $totalReturn = round($investmentAmount > 0 ? ($unrealizedProfit / $investmentAmount) * 100 : 0,2);

        // Fetch deductions
        $deductions = StockTransaction::where('portfolio_id', $portfolio->id)->sum('total_deductions');

        // Tax Payable (Example: 15% of Realized Profit)
        $taxPayable = max($realizedProfit * 0.15, 0);


        return view('portfolios.show', compact(
            'portfolio',
            'holdings',
            'investmentAmount',
            'unrealizedProfit',
            'realizedProfit',
            'todaysReturn',
            'totalReturn',
            'deductions',
            'marketValue',
            'taxPayable'
        ));
    }
    function formatNumber($number)
    {
        if ($number >= 1000000000) {
            return round($number / 1000000000, 2) . 'B';
        } elseif ($number >= 1000000) {
            return round($number / 1000000, 2) . 'M';
        } elseif ($number >= 1000) {
            return round($number / 1000, 2) . 'K';
        }
        return $number;
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
    //     private function isMarketOpen()
//     {
//         $now = now();
//         $openingTime = $now->setTime(9, 30);
//         $closingTime = $now->setTime(15, 30);
// dd("own : ", $now, $openingTime, $closingTime);

    //         return $now->isWeekday() && $now->between($openingTime, $closingTime);
//     }

    private function isMarketOpen()
    {
        $setting = SystemSettings::select('market_open_time', 'market_close_time')->first();
        $marketOpenTime = $setting->market_open_time;
        $marketCloseTime = $setting->market_close_time;
        $currentTime = now()->format('H:i');
        return now()->isWeekday() && $currentTime >= $marketOpenTime && $currentTime <= $marketCloseTime;
    }

    private function getStockPrices($holdings)
    {
        $isMarketOpen = $this->isMarketOpen();
        $lock = Cache::lock('stock_prices_lock', 60);
        if (!$lock->get()) {
            return Cache::get('stock_prices', []);
        }
        $prices = [];
        foreach ($holdings as $holding) {
            $symbol = $holding->stock->symbol;
            $response = Http::get("https://dps.psx.com.pk/timeseries/int/{$symbol}");

            if ($response->successful()) {
                $data = $response->json();
// dd("data : ", gettype($data), $data);
                $prices[$symbol] = $data['data'][0][1] ?? 0;
            }
        }

        Cache::put('stock_prices', $prices);
        $lock->release();
        return $prices;
    }

}
