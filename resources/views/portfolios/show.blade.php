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
                    <h6 class="card-title primary-purple"><span>Investment Amount</span></h6>
                    <h5>PKR {{ formatNumber($investmentAmount) }}</h5>
                </div>
            </div>
        </div>
        <!-- Unrealized Profit Card -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title primary-purple">Unrealized Profit</h6>
                    <h5><span class="{{ getProfitLossClass($unrealizedProfit) }} mx-1"></span>PKR
                        {{ formatNumber($unrealizedProfit) }}</h5>

                </div>
            </div>
        </div>
        <!-- Total Return (%) Card -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title primary-purple">Total Return (%)</h6>
                    <h5>PKR {{ formatNumber($totalReturn) }} (0.24%)</h5>
                </div>
            </div>
        </div>
        <!-- Today's Return (%) Card -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title primary-purple">Today's Return (%)</h6>
                    <h5>PKR {{ $todaysReturn }} (0.60%)</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Market Value Card -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title primary-purple">Market Value</h6>
                    <h5>PKR {{ formatNumber($marketValue) }}</h5>
                </div>
            </div>
        </div>
        <!-- Payouts Card -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title primary-purple">Payouts</h6>
                    <h5>PKR 0.00</h5>
                </div>
            </div>
        </div>
        <!-- Deductions Card -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title primary-purple">Deductions</h6>
                    <h5>PKR {{ $deductions }}</h5>
                </div>
            </div>
        </div>
        <!-- Realized Profit Card -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title primary-purple">Realized Profit</h6>
                    <h5>PKR {{ formatNumber($realizedProfit) }}</h5>
                </div>
            </div>
        </div>



    </div>

    <div class="row mt-3">

    </div>
    <!-- Tax Payable Card -->
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title primary-purple">Tax Payable</h6>
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
                                    <th>Name</th>
                                    <th>Description</th>
                                    @role(\App\Constants\AppConstant::ROLE_ADMIN)
                                        <th id="user-column-header">User</th>
                                    @endrole
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
            columns = [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'actionButton',
                    name: 'actionButton',
                    orderable: false,
                    searchable: false
                }
            ];
            if (userRole === ADMIN) {
                columns.splice(3, 0, {
                    data: 'user',
                    name: 'user',
                    render: function(data, type, row) {
                        return data ? data : 'N/A';
                    }
                });
            }
            console.log("columns : ", columns);
            $('#event-types_dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('portfolios.index') }}",
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
