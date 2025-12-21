@extends('layouts.app')
@section('page-title', 'Portfolio')
@section('main-page', 'Portfolio')
@section('sub-page', 'Holdings')
@section('content')

    <x-portfolio-component :portfolio="$data" />

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
                                    <th>Today P/L</th>
                                    <th>Total P/L</th>
                                    <th>Market Value</th>
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

    {{-- <input type="hidden" id="user-role" value="{{ auth()->user()->hasRole('admin') ? 'admin' : 'user' }}"> --}}

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
                { data: 'portfolio_percentage', name: 'portfolio_percentage' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ];
            console.log("columns : ", columns);
            $('#event-types_dataTable').DataTable({
                processing: true,
                serverSide: true,
                dom: `
                    <"d-flex justify-content-between align-items-center mb-3"
                        <"d-flex align-items-center"l>
                        <"d-flex align-items-center ms-auto"f>
                    >
                    <"table-responsive"rt>
                    <"d-flex justify-content-between align-items-center mt-3"
                        <"dataTables_info"i>
                        <"dataTables_paginate"p>
                    >
                `,
                ajax: "{{ route('portfolios.show', $portfolio->public_id) }}",
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
