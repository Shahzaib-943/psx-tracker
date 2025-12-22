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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $portfolios = Portfolio::with('user');
        if ($user->isUser()) {
            $portfolios = $portfolios->where('user_id', $user->id);
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
                ->setRowAttr([
                    'data-portfolio-id' => function ($row) {
                        return $row->public_id;
                    }
                ])
                ->addColumn('description', function ($row) {
                    return $row->description ? Str::limit($row->description, 20, ' ...') : '-';
                })
                ->addColumn('actionButton', function ($row) {
                    $actionButtons = '';
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    
                    // Check if user has permission to edit users using Spatie Permission
                    if ($user && $user->can('edit portfolios')) {
                        $editUrl = route('portfolios.edit', $row->public_id);
                        $actionButtons .= '<button type="button" class="btn btn-primary btn-icon me-2" onclick="window.location.href=\'' . $editUrl . '\'">
                            <i data-feather="edit"></i>
                        </button>';
                    }
                    
                    // Check if user has permission to delete users using Spatie Permission
                    if ($user && $user->can('delete portfolios')) {
                        $deleteUrl = route('portfolios.destroy', $row->public_id);
                        $actionButtons .= '<button type="button" class="btn btn-danger btn-icon delete-button" data-url="' . $deleteUrl . '" data-type="portfolio">
                            <i data-feather="trash-2"></i>
                        </button>';
                    }
                    
                    return $actionButtons ?: '-';
                })
                ->rawColumns(['actionButton', 'name']);
            /** @var \App\Models\User $user */
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
        $stocks = Stock::get(['id', 'name', 'symbol', 'slug']);
        $users = User::with('role')->get(['id', 'name']);
        return view('portfolios.create', compact('users', 'stocks'));
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
        $this->authorize('view', $portfolio);
        $holdingsQuery = $portfolio->holdings()->with('stock');
        if (request()->ajax()) { // DataTables request for holdings
            $holdings = $holdingsQuery->get();
            $stockPrices = $this->getStockPrices($portfolio);
            $investmentAmount = $holdings->sum('total_investment');
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
                    return '<button class="btn btn-sm btn-primary"><i data-feather="edit"></i></button> <button class="btn btn-sm btn-danger"><i data-feather="trash-2"></i></button>';
                })
                ->rawColumns(['symbol', 'quantity', 'avg_price', 'current_price', 'today_pnl', 'total_pnl', 'market_value', 'portfolio_percentage', 'action'])
                ->make(true);
        }

        // For initial page load, keep it light and load stats asynchronously (similar to dashboard)
        $holdings = $holdingsQuery->get();
        return view('portfolios.show', compact('portfolio', 'holdings'));
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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            $portfolios = Portfolio::get(['name', 'public_id']);
        } elseif ($user->isUser()) {
            $portfolios = Portfolio::where('user_id', $user->id)->get(['name', 'slug', 'public_id']);
        }
        $stocks = Stock::get(['id', 'name', 'symbol', 'slug']);
        return view('portfolios.trade-stocks', compact('portfolios', 'stocks'));
    }

    private function isMarketOpen()
    {
        $marketOpeningTime = SystemSettings::get('market_opening_time', '09:00');
        $marketClosingTime = SystemSettings::get('market_closing_time', '17:00');
        $currentTime = now()->format('H:i');
        return now()->isWeekday() && $currentTime >= $marketOpeningTime && $currentTime < $marketClosingTime;
    }

    private function getStockPrices($portfolio)
    {
        $isMarketOpen = $this->isMarketOpen();
        
        try {
            $holdings = $portfolio->holdings()->with('stock:id,symbol')->get();
            $prices = [];
            
            foreach ($holdings as $holding) {
                $symbol = $holding->stock->symbol;
                // If market is closed, use closing price from database
                if (!$isMarketOpen) {
                    $stock = $holding->stock;
                    $today = now()->format('Y-m-d');
                    
                    // Check if stock has today's closing price
                    $hasTodayPrice = $stock->price_updated_at && 
                                    $stock->price_updated_at->format('Y-m-d') === $today && 
                                    $stock->closing_price !== null;
                    
                    if ($hasTodayPrice) {
                        // Use today's closing price
                        $prices[$symbol] = $stock->closing_price;
                    } else {
                        // Stock was added after closing or doesn't have today's price - fetch it now
                        try {
                            $response = Http::timeout(10)->get("https://dps.psx.com.pk/timeseries/int/{$symbol}");
                            if ($response->successful()) {
                                $data = $response->json();
                                $price = $data['data'][0][1] ?? 0;
                                $prices[$symbol] = $price;
                                
                                // Save it to database for future use
                                $stock->closing_price = $price;
                                $stock->price_updated_at = now();
                                $stock->save();
                            } else {
                                // Fallback to existing closing price or cache
                                $prices[$symbol] = $stock->closing_price ?? Cache::get("stock_price_{$symbol}", 0);
                            }
                        } catch (\Exception $e) {
                            Log::error("Error fetching price for newly added stock {$symbol}: " . $e->getMessage());
                            $prices[$symbol] = $stock->closing_price ?? Cache::get("stock_price_{$symbol}", 0);
                        }
                    }
                    continue;
                }
                // Market is open - fetch from API
                $response = Http::get("https://dps.psx.com.pk/timeseries/int/{$symbol}");
                if ($response->successful()) {
                    $data = $response->json();
                    $prices[$symbol] = $data['data'][0][1] ?? 0;
                } else {
                    Log::error("Failed to fetch price for {$symbol}. Response: " . $response->body());
                    // Try to get from cache or database as fallback
                    $prices[$symbol] = Cache::get("stock_price_{$symbol}", $holding->stock->closing_price ?? 0);
                }
            }
        } catch (\Exception $e) {
            Log::error("Error fetching stock prices: " . $e->getMessage());
            // Return cached prices or closing prices as fallback
            $cachedPrices = Cache::get('portfolio_' . $portfolio->id, []);
            if (empty($cachedPrices)) {
                $holdings = $portfolio->holdings()->with('stock')->get();
                foreach ($holdings as $holding) {
                    $cachedPrices[$holding->stock->symbol] = $holding->stock->closing_price ?? 0;
                }
            }
            return $cachedPrices;
        }
        return $prices;
    }

    /**
     * Get portfolio stats for dashboard
     */
    public function getStats(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $portfolioId = $request->input('portfolio_id', 'all');
        $format = $request->input('format', 'json'); // 'json' or 'html'

        // Get portfolios based on user role
        if ($user->isAdmin()) {
            $portfoliosQuery = Portfolio::with('holdings.stock');
        } else {
            $portfoliosQuery = Portfolio::with('holdings.stock')->where('user_id', $user->id);
        }

        // Get all portfolios for dropdown
        $allPortfolios = $portfoliosQuery->get(['id', 'name', 'public_id']);

        // Calculate stats
        if ($portfolioId === 'all') {
            // Aggregate stats for all portfolios
            $portfolios = $portfoliosQuery->get();
            $allHoldings = $portfolios->flatMap->holdings;
            $investmentAmount = $allHoldings->sum('total_investment');
            
            // Get stock prices for all unique stocks
            $allStocks = $allHoldings->pluck('stock')->unique('id');
            $stockPrices = [];
            $isMarketOpen = $this->isMarketOpen();
            
            foreach ($allStocks as $stock) {
                // If market is closed, use closing price from database
                if (!$isMarketOpen) {
                    $today = now()->format('Y-m-d');
                    
                    // Check if stock has today's closing price
                    $hasTodayPrice = $stock->price_updated_at && 
                                    $stock->price_updated_at->format('Y-m-d') === $today && 
                                    $stock->closing_price !== null;
                    
                    if ($hasTodayPrice) {
                        // Use today's closing price
                        $stockPrices[$stock->symbol] = $stock->closing_price;
                    } else {
                        // Stock was added after closing or doesn't have today's price - fetch it now
                        try {
                            $response = Http::timeout(10)->get("https://dps.psx.com.pk/timeseries/int/{$stock->symbol}");
                            if ($response->successful()) {
                                $data = $response->json();
                                $price = $data['data'][0][1] ?? 0;
                                $stockPrices[$stock->symbol] = $price;
                                
                                // Save it to database for future use
                                $stock->closing_price = $price;
                                $stock->price_updated_at = now();
                                $stock->save();
                            } else {
                                // Fallback to existing closing price or cache
                                $stockPrices[$stock->symbol] = $stock->closing_price ?? Cache::get("stock_price_{$stock->symbol}", 0);
                            }
                        } catch (\Exception $e) {
                            Log::error("Error fetching price for newly added stock {$stock->symbol}: " . $e->getMessage());
                            $stockPrices[$stock->symbol] = $stock->closing_price ?? Cache::get("stock_price_{$stock->symbol}", 0);
                        }
                    }
                    continue;
                }
                
                // Market is open - fetch from API
                try {
                    $response = Http::timeout(10)->get("https://dps.psx.com.pk/timeseries/int/{$stock->symbol}");
                    if ($response->successful()) {
                        $data = $response->json();
                        $stockPrices[$stock->symbol] = $data['data'][0][1] ?? 0;
                        Cache::put("stock_price_{$stock->symbol}", $stockPrices[$stock->symbol], now()->addHours(1));
                    } else {
                        $stockPrices[$stock->symbol] = Cache::get("stock_price_{$stock->symbol}", $stock->closing_price ?? 0);
                    }
                } catch (\Exception $e) {
                    $stockPrices[$stock->symbol] = Cache::get("stock_price_{$stock->symbol}", $stock->closing_price ?? 0);
                }
            }

            $marketValue = $allHoldings->sum(function ($holding) use ($stockPrices) {
                $currentPrice = $stockPrices[$holding->stock->symbol] ?? 0;
                return $currentPrice * $holding->quantity;
            });

            $realizedProfit = StockTransaction::whereIn('portfolio_id', $portfolios->pluck('id'))
                ->where('transaction_type', 'sell')
                ->sum('net_amount');

            $todaysReturn = $allHoldings->sum(function ($holding) {
                return ($holding->stock->current_price - $holding->stock->previous_close) * $holding->quantity;
            });
        } else {
            // Stats for specific portfolio - find by public_id
            $portfolio = Portfolio::with('holdings.stock')->where('public_id', $portfolioId)->firstOrFail();
            $this->authorize('view', $portfolio);
            
            $holdings = $portfolio->holdings;
            $investmentAmount = $holdings->sum('total_investment');
            $stockPrices = $this->getStockPrices($portfolio);
            
            $marketValue = $holdings->sum(function ($holding) use ($stockPrices) {
                $currentPrice = $stockPrices[$holding->stock->symbol] ?? 0;
                return $currentPrice * $holding->quantity;
            });

            $realizedProfit = StockTransaction::where('portfolio_id', $portfolio->id)
                ->where('transaction_type', 'sell')
                ->sum('net_amount');

            $todaysReturn = $holdings->sum(function ($holding) {
                return ($holding->stock->current_price - $holding->stock->previous_close) * $holding->quantity;
            });
        }

        $unrealizedProfit = round($marketValue - $investmentAmount, 2);
        $totalReturn = round($investmentAmount > 0 ? ($unrealizedProfit / $investmentAmount) * 100 : 0, 2);

        $data = [
            'investmentAmount' => $investmentAmount,
            'unrealizedProfit' => $unrealizedProfit,
            'realizedProfit' => $realizedProfit,
            'todaysReturn' => $todaysReturn,
            'totalReturn' => $totalReturn,
            'marketValue' => $marketValue,
        ];

        // Common portfolios list for both JSON and HTML responses
        $portfoliosList = $allPortfolios->map(function ($portfolio) {
            return [
                'id' => $portfolio->public_id,
                'name' => $portfolio->name,
            ];
        })->values()->toArray(); // Ensure it's always an array

        // If format is HTML, render the component and return HTML
        if ($format === 'html') {
            $html = view('components.portfolio-component', ['portfolio' => $data])->render();
            return response()->json([
                'html' => $html,
                'portfolios' => $portfoliosList,
            ]);
        }

        // Default: return JSON (for dashboard)
        return response()->json([
            'data' => $data,
            'portfolios' => $portfoliosList,
        ]);
    }

}
