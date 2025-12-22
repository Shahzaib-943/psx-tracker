@extends('layouts.app')
@section('page-title', 'Portfolio')
@section('main-page', 'Portfolio')
@section('sub-page', 'Holdings')
@section('content')

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Portfolio - {{ $portfolio->name }}</h5>
        </div>
        <div class="card-body">
            <div id="portfolio-show-loader" class="text-center py-5">
                <img src="https://api.iconify.design/svg-spinners:blocks-shuffle-3.svg?color=%23570be5" alt="">
            </div>
            <div id="portfolio-show-content" style="display: none;">
                <!-- Portfolio stats will be loaded here via AJAX -->
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

            // Load portfolio stats for this specific portfolio via Blade component (async)
            $('#portfolio-show-loader').show();
            $('#portfolio-show-content').hide();

            $.ajax({
                url: '{{ route('portfolios.stats') }}',
                type: 'GET',
                data: {
                    portfolio_id: @json($portfolio->public_id),
                    format: 'html'
                },
                success: function(response) {
                    if (!response || !response.html) {
                        $('#portfolio-show-loader').hide();
                        $('#portfolio-show-content').html(`
                            <div class="alert alert-info text-center">
                                <i data-feather="info" class="me-2"></i>
                                <strong>No portfolio data available.</strong>
                            </div>
                        `).show();
                        feather.replace();
                        return;
                    }

                    $('#portfolio-show-content').html(response.html);
                    $('#portfolio-show-loader').hide();
                    $('#portfolio-show-content').show();
                    feather.replace();
                },
                error: function(xhr, status, error) {
                    console.error('Error loading portfolio overview:', error, xhr.responseJSON);
                    $('#portfolio-show-loader').hide();
                    $('#portfolio-show-content').html(`
                        <div class="alert alert-danger text-center">
                            <i data-feather="alert-circle" class="me-2"></i>
                            <strong>Failed to load portfolio statistics.</strong> Please try again.
                        </div>
                    `).show();
                    feather.replace();
                }
            });

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
