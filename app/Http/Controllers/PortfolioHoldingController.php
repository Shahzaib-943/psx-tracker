<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Portfolio;
use Illuminate\Http\Request;
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
        $portfolioSlug = $validatedData['portfolio'];
        $stockSlug = $validatedData['stock'];
        $portfolio = Portfolio::where('slug', $portfolioSlug)->first();
        $stock = Stock::where('slug', $stockSlug)->first();
        if (!$portfolio || !$stock) {
            return back()->withErrors(['error' => 'Invalid Portfolio or Stock selected']);
        }
        $validatedData['portfolio_id'] = $portfolio->id;
        $validatedData['stock_id'] = $stock->id;
if(isset($validatedData['make_deductions'])) {
        $validatedData['broker_commission'] = round($this->getBrokerCommission($validatedData['price_per_share'], $validatedData['quantity']));
}
dd("Commission : " ,$validatedData['broker_commission']);
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
        if ($price >= 19.99) {
            return ($price * $quantity) * 0.0015;
        } else {
            return $quantity * 0.03;
        }
    }
}
