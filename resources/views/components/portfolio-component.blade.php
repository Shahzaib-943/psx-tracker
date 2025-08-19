<div>
    <div class="row">
        <!-- Investment Amount Card -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-primary"><span>Investment Amount</span></h6>
                    <h5>PKR {{ formatNumber($portfolio['investmentAmount']) }}</h5>
                </div>
            </div>
        </div>
        <!-- Market Value Card -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-primary">Market Value</h6>
                    <h5><span class="{{ getProfitLossClass($portfolio['marketValue']) }} mx-1"></span>PKR {{
                        formatNumber($portfolio['marketValue']) }}</h5>
                </div>
            </div>
        </div>
        <!-- Unrealized Profit Card -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-primary">Unrealized P/L</h6>
                    <h5><span class="{{ getProfitLossClass($portfolio['unrealizedProfit']) }} mx-1"></span>PKR
                        {{ formatNumber($portfolio['unrealizedProfit']) }} <span
                            class="{{ formatPercentageClass($portfolio['totalReturn']) }}">{{ $portfolio['totalReturn']
                            }} %</span></h5>

                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <!-- Today's Return (%) Card -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-primary">Today's Return (%)</h6>
                    <h5>PKR {{ $portfolio['todaysReturn'] }} <span class="badge bg-success">(0.60%)</span></h5>
                </div>
            </div>
        </div>
        <!-- Total Return (%) Card -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-primary" title="Includes Capital Gain & Loss + Dividends">Total Return
                        (%)
                    </h6>
                    <h5><span class="{{ getProfitLossClass($portfolio['unrealizedProfit']) }} mx-1"></span>PKR
                        {{ formatNumber($portfolio['unrealizedProfit']) }} <span
                            class="{{ formatPercentageClass($portfolio['totalReturn']) }}">{{ $portfolio['totalReturn']
                            }} %</span>
                    </h5>
                </div>
            </div>
        </div>
        <!-- Payouts Card -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-primary">Payouts</h6>
                    <h5>PKR 0.00</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <!-- Realized Profit Card -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-primary">Realized P/L</h6>
                    <h5>PKR {{ formatNumber($portfolio['realizedProfit']) }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>