@extends('layouts.app')
@section('page-title', 'Portfolio')
@section('main-page', 'Portfolio')
@section('sub-page', 'Holdings')
@section('content')

    <div class="row">
        <!-- Investment Amount Card -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-primary"><span>Investment Amount</span></h6>
                    <h5>PKR {{ formatNumber($investmentAmount) }}</h5>
                </div>
            </div>
        </div>
        <!-- Unrealized Profit Card -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-primary">Unrealized Capital Gain/Loss</h6>
                    <h5><span class="{{ getProfitLossClass($unrealizedProfit) }} mx-1"></span>PKR
                        {{ formatNumber($unrealizedProfit) }}</h5>

                </div>
            </div>
        </div>
        <!-- Total Return (%) Card -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-primary" title="Includes Capital Gain & Loss + Dividends">Total Return (%)
                    </h6>
                    <h5><span class="{{ getProfitLossClass($unrealizedProfit) }} mx-1"></span>PKR
                        {{ formatNumber($unrealizedProfit) }} <span class="{{ formatPercentageClass($totalReturn) }}">{{ $totalReturn }} %</span>
                    </h5>
                </div>
            </div>
        </div>
        <!-- Today's Return (%) Card -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-primary">Today's Return (%)</h6>
                    <h5>PKR {{ $todaysReturn }} <span class="badge bg-success">(0.60%)</span></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Market Value Card -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-primary">Market Value</h6>
                    <h5>PKR {{ formatNumber($marketValue) }}</h5>
                </div>
            </div>
        </div>
        <!-- Payouts Card -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-primary">Payouts</h6>
                    <h5>PKR 0.00</h5>
                </div>
            </div>
        </div>
        <!-- Deductions Card -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-primary">Deductions</h6>
                    <h5>PKR {{ $deductions }}</h5>
                </div>
            </div>
        </div>
        <!-- Realized Profit Card -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-primary">Realized Profit</h6>
                    <h5>PKR {{ formatNumber($realizedProfit) }}</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Tax Payable Card -->
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-primary">Tax Payable</h6>
                <h5>PKR 0.30</h5>
            </div>
        </div>
    </div>
    <!-- Data Table -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Holding Stocks</h6>
                    <div class="table-responsive">
                        <table id="event-types_dataTable" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Symbol</th>
                                    <th>Quantity</th>
                                    <th>Avg Price</th>
                                    <th>Current Price</th>
                                    <th>Today P/L<th>
                                    <th>Total P/L<th>
                                    <th>Market Value</th>
                                    <th>Portfolio %</th>
                                    <th>Portfolio %</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="user-role" value="{{ auth()->user()->hasRole('admin') ? 'admin' : 'user' }}">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            let userRole = @json(auth()->user()->getRoleNames()->first());
            const ADMIN = @json(\App\Constants\AppConstant::ROLE_ADMIN);
            columns = [
{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'symbol', name: 'symbol' },
            { data: 'quantity', name: 'quantity' },
            { data: 'avg_price', name: 'avg_price' },
            { data: 'current_price', name: 'current_price' },
            { data: 'today_pnl', name: 'today_pnl' },
            { data: 'total_pnl', name: 'total_pnl' },
            { data: 'market_value', name: 'market_value' },
            { data: 'portfolio_percentage', name: 'portfolio_percentage' },];
            console.log("columns : ", columns);
            $('#event-types_dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('portfolios.show', $portfolio->slug) }}",
                columns: columns,
                drawCallback: function(settings) {
                    feather.replace();
                },
            });
        });
    </script>

    @push('scripts')
        <script src="{{ asset('nobleui/assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('nobleui/assets/js/data-table.js') }}"></script>
        <script src="{{ asset('nobleui/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js') }}"></script>
    @endpush
@endsection
