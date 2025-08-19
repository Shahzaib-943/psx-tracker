<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stock;
use App\Models\Portfolio;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SystemSettings;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
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
                    $portfolioUrl = route('portfolios.show', $row->public_id);
                    $actionButton = '<a  type="button" onclick="window.location.href=\'' . $portfolioUrl . '\'">
                    ' . $row->name . '
                    </a>';
                    return $actionButton;

                })
                ->addColumn('description', function ($row) {
                    return Str::limit($row->description, 20, ' ...');
                })
                ->addColumn('actionButton', function ($row) {
                    $editUrl = route('portfolios.edit', $row->public_id);
                    $deleteUrl = route('portfolios.destroy', $row->public_id);
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
    //     public function show(Portfolio $portfolio)
//     {
//         $holdingStocks = query();
//         // $user = auth()->user();
//         // $portfolio->load('holdings:id,portfolio_id,stock_id,quantity,average_cost,total_investment','holdings.stock:id,symbol');
//         // $portfolioHoldings = $portfolio->holdings->toArray();
//         // dd("portfolioHoldings : ", $portfolioHoldings);
//         // $portfolios = Portfolio::query();
//         // if ($user->isUser()) {
//         //     $portfolios = Portfolio::with('user')->where('user_id', $user->id);
//         // }
//         // if ($request->ajax()) {
//         //     $table = DataTables::of($portfolios)
//         //         ->addIndexColumn()
//         //         ->addColumn('user', function ($row) {
//         //             if ($row->user) {
//         //                 $roleName = $row->user->roles->isNotEmpty() ? $row->user->roles->first()->name : 'No Role';
//         //                 return $row->user->name . ' (' . ucfirst($roleName) . ')';
//         //             }
//         //         })
//         //         ->addColumn('name', function ($row) {
//         //             $portfolioUrl = route('portfolios.show', $row->slug);
//         //             $actionButton = '<a  type="button" onclick="window.location.href=\'' . $portfolioUrl . '\'">
//         //             ' . $row->name . '
//         //             </a>';
//         //             return $actionButton;

    //         //         })
//         //         ->addColumn('description', function ($row) {
//         //             return Str::limit($row->description, 20, ' ...');
//         //         })
//         //         ->addColumn('actionButton', function ($row) {
//         //             $editUrl = route('portfolios.edit', $row->slug);
//         //             $deleteUrl = route('portfolios.destroy', $row->slug);
//         //             $actionButtons = '<button type="button" class="btn btn-primary btn-icon" onclick="window.location.href=\'' . $editUrl . '\'">
//         //             <i data-feather="edit"></i>
//         //             </button>
//         //             <button type="button" class="btn btn-danger btn-icon delete-button" data-url="' . $deleteUrl . '" data-type="portfolio">
//         //                 <i data-feather="trash-2"></i>
//         //             </button>';
//         //             return $actionButtons;
//         //         })
//         //         ->rawColumns(['actionButton', 'name']);
//         //     if (!$user->isAdmin()) {
//         //         $table->removeColumn('user');
//         //         $table->removeColumn('is_common');
//         //     }
//         //     return $table->make(true);
//         // }
// // Fetch holdings with related stocks
//         $holdings = $portfolio->holdings()->with('stock')->get();
// // dd("holdings", $holdings);
//         // Calculate investment amount
//         $investmentAmount = $holdings->sum('total_investment');
// // dd("a");
// // dd("aaa", $investmentAmount);
//         $stockPrices = $this->getStockPrices($holdings);
//         // Unrealized Profit (Market Value - Investment)
//         $marketValue = $holdings->sum(fn($holding) => ($stockPrices[$holding->stock->symbol] ?? 0) * $holding->quantity);
// // dd("market value : ", $marketValue);
// // Unrealized Profit
//         $unrealizedProfit = round($marketValue - $investmentAmount,2);

    //         // dd("investmentAmount", $prices);

    //         // Realized Profit from transactions table (sum of sell transactions)
//         $realizedProfit = StockTransaction::where('portfolio_id', $portfolio->id)
//             ->where('transaction_type', 'sell')
//             ->sum('net_amount');

    //         // Today's Return (change in stock price * quantity)
//         $todaysReturn = $holdings->sum(fn($holding) => ($holding->stock->current_price - $holding->stock->previous_close) * $holding->quantity);

    //         // Total Return %
//         $totalReturn = round($investmentAmount > 0 ? ($unrealizedProfit / $investmentAmount) * 100 : 0,2);

    //         // Fetch deductions
//         $deductions = StockTransaction::where('portfolio_id', $portfolio->id)->sum('total_deductions');

    //         // Tax Payable (Example: 15% of Realized Profit)
//         $taxPayable = max($realizedProfit * 0.15, 0);

    // Log::info("AAA", [formatPercentageClass($totalReturn)]);
//         return view('portfolios.show', compact(
//             'portfolio',
//             'holdings',
//             'investmentAmount',
//             'unrealizedProfit',
//             'realizedProfit',
//             'todaysReturn',
//             'totalReturn',
//             'deductions',
//             'marketValue',
//             'taxPayable'
//         ));
//     }

    public function show(Portfolio $portfolio)
    {
        $holdingsQuery = $portfolio->holdings()->with('stock');
        $holdings = $holdingsQuery->get();
        $stockPrices = $this->getStockPrices($portfolio);
        // dd($stockPrices);
        $investmentAmount = $holdings->sum('total_investment');
        if (request()->ajax()) {
            return DataTables::of($holdingsQuery)
                ->addIndexColumn()
                ->addColumn('symbol', fn($holding) => $holding->stock->symbol)
                ->addColumn('quantity', fn($holding) => $holding->quantity)
                ->addColumn('avg_price', fn($holding) => formatNumber($holding->average_cost))
                ->addColumn('current_price', fn($holding) => formatNumber($stockPrices[$holding->stock->symbol] ?? 0))
                ->addColumn('today_pnl', fn($holding) => formatNumber(0))
                ->addColumn('total_pnl', function ($holding) use ($stockPrices) {
                    $currentPrice = $stockPrices[$holding->stock->symbol] ?? 0;
                    return formatNumber(($currentPrice * $holding->quantity) - $holding->total_investment);
                })
                ->addColumn('market_value', function ($holding) use ($stockPrices) {
                    $currentPrice = $stockPrices[$holding->stock->symbol] ?? 0;
                    return formatNumber($currentPrice * $holding->quantity);
                })
                ->addColumn('portfolio_percentage', function ($holding) use ($stockPrices, $holdings) {
                    $currentPrice = $stockPrices[$holding->stock->symbol] ?? 0;
                    $totalMarketValue = $holdings->sum(fn($h) => ($stockPrices[$h->stock->symbol] ?? 0) * $h->quantity);
                    return $totalMarketValue > 0 ? round(($currentPrice * $holding->quantity) / $totalMarketValue * 100, 2) . '%' : '0%';
                })
                ->addColumn('action', function ($holding) {
                    return '<button class="btn btn-sm btn-primary">Edit</button> <button class="btn btn-sm btn-danger">Delete</button>';
                })
                ->rawColumns(['symbol', 'quantity', 'avg_price', 'current_price', 'today_pnl', 'total_pnl', 'market_value', 'portfolio_percentage', 'action'])
                ->make(true);
        }


        $marketValue = $holdings->sum(fn($holding) => ($stockPrices[$holding->stock->symbol] ?? 0) * $holding->quantity);
        $unrealizedProfit = round($marketValue - $investmentAmount, 2);
        $realizedProfit = StockTransaction::where('portfolio_id', $portfolio->id)
            ->where('transaction_type', 'sell')
            ->sum('net_amount');
        $todaysReturn = $holdings->sum(fn($holding) => ($holding->stock->current_price - $holding->stock->previous_close) * $holding->quantity);
        $totalReturn = round($investmentAmount > 0 ? ($unrealizedProfit / $investmentAmount) * 100 : 0, 2);
        $deductions = StockTransaction::where('portfolio_id', $portfolio->id)->sum('total_deductions');
        $taxPayable = max($realizedProfit * 0.15, 0);
        $data = [
            'investmentAmount' => $investmentAmount,
            'unrealizedProfit' => $unrealizedProfit,
            'realizedProfit' => $realizedProfit,
            'todaysReturn' => $todaysReturn,
            'totalReturn' => $totalReturn,
            'deductions' => $deductions,
            'marketValue' => $marketValue,
            'taxPayable' => $taxPayable
        ];
        return view('portfolios.show', compact(
            'portfolio',
            'holdings',
            'data'
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
            $portfolios = Portfolio::get(['name', 'public_id']);
        } elseif ($user->isUSer()) {
            $portfolios = Portfolio::where('user_id', $user->id)->get(['name', 'slug', 'public_id']);
        }
        $stocks = Stock::get(['id', 'name', 'symbol', 'slug']);
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

    private function getStockPrices($portfolio)
    {
        
        // $isMarketOpen = $this->isMarketOpen();
        // // $isMarketOpen = true;
        // if (!$isMarketOpen) {
        //     return Cache::get('portfolio_' . $portfolio->id, []);
        // }
        // $lock = Cache::lock('portfolio_' . $portfolio->id, 60);
        // if (!$lock->get()) {
        //     return Cache::get('portfolio_' . $portfolio->id, []);
        // }
        try {
            $holdings = $portfolio->holdings()->with('stock')->get();
            $prices = [];
            foreach ($holdings as $holding) {
                $symbol = $holding->stock->symbol;
                $response = Http::get("https://dps.psx.com.pk/timeseries/int/{$symbol}");
                if ($response->successful()) {
                    $data = $response->json();
                    $prices[$symbol] = $data['data'][0][1] ?? 0;
                } else {
                    Log::error("Failed to fetch price for {$symbol}. Response: " . $response->body());
                    $prices[$symbol] = Cache::get("stock_price_{$symbol}", 0);
                }
            }
            Cache::put('portfolio_' . $portfolio->id, $prices);
        } catch (\Exception $e) {
            Log::error("Error fetching stock prices: " . $e->getMessage());
        } finally {
            // $lock->release();
        }
        return Cache::get('portfolio_' . $portfolio->id, []);
    }

}
