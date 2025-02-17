<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use App\Models\PortfolioHolding;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePortfolioHoldingRequest;

class PortfolioHoldingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePortfolioHoldingRequest $request)
    {
        $validatedData = $request->validated();

        $portfolio = Portfolio::where('slug', $validatedData['portfolio'])->firstOrFail();
        $stock = Stock::where('slug', $validatedData['stock'])->firstOrFail();

        $validatedData['portfolio_id'] = $portfolio->id;
        $validatedData['stock_id'] = $stock->id;
        $validatedData['transaction_type'] = 'buy';

        $pricePerShare = $validatedData['price_per_share'];
        $quantity = $validatedData['quantity'];
        $grossAmount = $pricePerShare * $quantity;

        $brokerCommissionPerShare = $this->getBrokerCommission($pricePerShare, 1);
        $totalBrokerCommission = $brokerCommissionPerShare * $quantity;

        $totalDeductions = $totalBrokerCommission; // In the future, add more deductions here.
        $netAmount = $grossAmount + $totalDeductions;
        $finalPricePerShare = $pricePerShare + $brokerCommissionPerShare;
        DB::beginTransaction();

        try {
            // return DB::transaction(function () use ($validatedData, $grossAmount, final_price_per_share, $totalBrokerCommission, $totalDeductions, $netAmount, $brokerCommissionPerShare) {
            // Store transaction
            StockTransaction::create([
                'portfolio_id' => $validatedData['portfolio_id'],
                'stock_id' => $validatedData['stock_id'],
                'transaction_type' => $validatedData['transaction_type'],
                'quantity' => $validatedData['quantity'],
                'price_per_share' => $validatedData['price_per_share'],
                'gross_amount' => $grossAmount,
                'broker_commission' => $brokerCommissionPerShare,
                'final_price_per_share' => $finalPricePerShare,
                'total_deductions' => $totalDeductions,
                'net_amount' => $netAmount,
                'transaction_date' => $validatedData['transaction_date'],
                'notes' => $validatedData['notes'] ?? null,
            ]);

            // Update or create portfolio holding
            PortfolioHolding::updateOrCreate(
                [
                    'portfolio_id' => $validatedData['portfolio_id'],
                    'stock_id' => $validatedData['stock_id'],
                ],
                [
                    'quantity' => DB::raw("quantity + {$validatedData['quantity']}"),
                    'total_investment' => DB::raw("total_investment + {$netAmount}"),
                    'average_cost' => DB::raw("(total_investment + {$netAmount}) / (quantity + {$validatedData['quantity']})"),
                ]
            );
            DB::commit();
            // return redirect()->route('portfolio.show', $validatedData['portfolio_id'])
            //     ->with('success', 'Shares purchased successfully');
            // });
        } catch (\Exception $e) {
            Log::error("Transaction failed: " . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Transaction failed: ' . $e->getMessage()]);
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
    public function destroy(string $id)
    {
        //
    }
    private function getBrokerCommission($price, $quantity)
    {
        // Commission calculation based on the uploaded structure
        if ($price <= 4.99) {
            // 3p or 0.15% (whichever is higher)
            $commission = max(0.03, $price * 0.0015);
        } elseif ($price <= 99.99) {
            // 3p or 0.15% (whichever is higher)
            $commission = max(0.03, $price * 0.0015);
        } elseif ($price <= 199.99) {
            // 3p or 0.15% (whichever is higher)
            $commission = max(0.03, $price * 0.0015);
        } else { // $price >= 200.00
            // 3p or 0.15% (whichever is higher)
            $commission = max(0.03, $price * 0.0015);
        }
        return round($commission, 2);
        // return $total_value * 0.0001; // 0.01% for 5.00 and above
    }

}
