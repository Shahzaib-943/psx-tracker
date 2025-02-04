@extends('layouts.app')
@section('page-title', 'Dashboard')
@section('content')

    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
            </div>
        </div>

        <div class="row justify-content-center">
            <div id="dashboard-content">
                <!-- This is where the dashboard content will be updated dynamically after applying filters -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Dashboard Overview</h5>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <p class="mb-0">{{ __('You are logged in! Explore your financial summary below.') }}</p>
                    </div>
                </div>

                @if ($chartData && count($chartData['labels']) > 0)
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <input type="text" id="date-range" class="form-control me-2"
                                    placeholder="Select Date Range">
                                <select id="finance-type" class="form-select me-2">
                                    <option value="">All Types</option>
                                    @foreach ($financeTypes as $financeType)
                                        <option value="{{ $financeType->id }}">{{ $financeType->name }}</option>
                                    @endforeach
                                </select>
                                <button id="apply-filter" class="btn btn-primary">Apply Filter</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="card-title mb-0">Finance Overview</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="financeChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    @endpush --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

    @push('scripts')
        {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>



        <script>
            $(document).ready(function() {
                // Initialize Date Range Picker
                $('#date-range').daterangepicker({
                    locale: {
                        format: 'MMM D, YYYY',
                        cancelLabel: 'Clear'
                    },
                    autoUpdateInput: false,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')],
                    }
                });

                // Handle date selection
                $('#date-range').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('MMM D, YYYY') + ' - ' + picker.endDate.format(
                        'MMM D, YYYY'));
                });

                // Handle clear action
                $('#date-range').on('cancel.daterangepicker', function() {
                    $(this).val('');
                });


                // Initialize Date Range Picker
                $('#apply-filter').on('click', function() {
                    const dateRange = $('#date-range').val();
                    const financeType = $('#finance-type').val();

                    // Split date range into start and end dates
                    const dates = dateRange.split(' - ');
                    const startDate = dates[0] ? moment(dates[0], 'MMM D, YYYY').format('YYYY-MM-DD') : null;
                    const endDate = dates[1] ? moment(dates[1], 'MMM D, YYYY').format('YYYY-MM-DD') : null;

                    // Send AJAX request with filters
                    $.ajax({
                        url: '{{ route('home') }}', // Adjust this route if needed
                        type: 'GET',
                        data: {
                            start_date: startDate,
                            end_date: endDate,
                            finance_type: financeType
                        },
                        success: function(response) {
                            if (response.chartData && response.chartData.labels.length > 0) {
                                financeChart.data.labels = response.chartData.labels;
                                financeChart.data.datasets[0].data = response.chartData.values;
                                financeChart.data.datasets[0].backgroundColor = response.chartData
                                    .colors;
                                financeChart.update();
                            } else {
                                // Handle case when no data is returned
                                alert('No data found for the selected filters.');
                            }
                        },
                        error: function() {
                            alert('Failed to fetch data. Please try again.');
                        }
                    });
                });

                // Chart Data Initialization
                const chartData = @json($chartData);

                let financeChart;
                if (chartData && chartData.labels.length > 0) {
                    const ctx = document.getElementById('financeChart').getContext('2d');
                    financeChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                label: 'Amount',
                                data: chartData.values,
                                backgroundColor: chartData.colors,
                                borderColor: '#fff',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let value = context.raw;
                                            return `Rs ${value}`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
