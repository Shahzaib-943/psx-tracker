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
                <!-- Portfolio Stats Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Portfolio Statistics</h5>
                        <div class="d-flex align-items-center">
                            <label for="portfolio-select" class="me-2 mb-0">Portfolio:</label>
                            <select id="portfolio-select" class="form-select" style="width: auto;">
                                <option value="all">All Portfolios</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="portfolio-stats-loader" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading portfolio statistics...</p>
                        </div>
                        <div id="portfolio-stats-content" style="display: none;">
                            <!-- Portfolio component will be loaded here via AJAX -->
                        </div>
                    </div>
                </div>
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
                // Load portfolio stats on page load
                loadPortfolioStats('all');

                // Handle portfolio dropdown change
                $('#portfolio-select').on('change', function() {
                    const portfolioId = $(this).val();
                    loadPortfolioStats(portfolioId);
                });

                // Function to load portfolio stats via AJAX
                function loadPortfolioStats(portfolioId) {
                    $('#portfolio-stats-loader').show();
                    $('#portfolio-stats-content').hide();

                    $.ajax({
                        url: '{{ route('portfolios.stats') }}',
                        type: 'GET',
                        data: {
                            portfolio_id: portfolioId
                        },
                        success: function(response) {
                            console.log('Portfolio stats response:', response);
                            
                            // Check if response is valid
                            if (!response) {
                                $('#portfolio-stats-loader').hide();
                                $('#portfolio-stats-content').html(`
                                    <div class="alert alert-danger text-center">
                                        <i data-feather="alert-circle" class="me-2"></i>
                                        <strong>Error loading portfolio data.</strong> Please try again.
                                    </div>
                                `).show();
                                feather.replace();
                                return;
                            }

                            // Check if user has no portfolios (this should be the only case showing the message)
                            // Only show message if portfolios array is empty or doesn't exist
                            const hasPortfolios = response.portfolios && Array.isArray(response.portfolios) && response.portfolios.length > 0;
                            
                            if (!hasPortfolios) {
                                $('#portfolio-stats-loader').hide();
                                $('.card-header .d-flex').hide(); // Hide dropdown
                                $('#portfolio-stats-content').html(`
                                    <div class="alert alert-info text-center">
                                        <i data-feather="info" class="me-2"></i>
                                        <strong>No portfolios found.</strong> 
                                        <a href="{{ route('portfolios.create') }}" class="none-link">Create a portfolio to view statistics.</a>
                                        <style>
                                            .none-link {
                                                color: inherit;
                                                text-decoration: none;
                                            }
                                        </style>
                                    </div>
                                `).show();
                                feather.replace();
                                return;
                            }
                            
                            // Show dropdown if portfolios exist
                            $('.card-header .d-flex').show();

                            // Update dropdown options
                            const select = $('#portfolio-select');
                            select.empty();
                            select.append('<option value="all">All Portfolios</option>');
                            
                            response.portfolios.forEach(function(portfolio) {
                                select.append(`<option value="${portfolio.id}">${portfolio.name}</option>`);
                            });
                            
                            // Set selected value
                            select.val(portfolioId);

                            // Check if data is available (portfolios exist but may have no holdings)
                            if (!response.data) {
                                $('#portfolio-stats-loader').hide();
                                $('#portfolio-stats-content').html(`
                                    <div class="alert alert-info text-center">
                                        <i data-feather="info" class="me-2"></i>
                                        <strong>No portfolio data available.</strong> Add holdings to your portfolios to see statistics.
                                    </div>
                                `).show();
                                feather.replace();
                                return;
                            }

                            // Render portfolio component
                            const portfolioData = response.data;
                            const componentHtml = `
                                <div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h6 class="card-title text-primary"><span>Investment Amount</span></h6>
                                                    <h5>PKR ${formatNumber(portfolioData.investmentAmount)}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h6 class="card-title text-primary">Market Value</h6>
                                                    <h5><span class="${getProfitLossClass(portfolioData.marketValue)} mx-1"></span>PKR ${formatNumber(portfolioData.marketValue)}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h6 class="card-title text-primary">Unrealized P/L</h6>
                                                    <h5><span class="${getProfitLossClass(portfolioData.unrealizedProfit)} mx-1"></span>PKR ${formatNumber(portfolioData.unrealizedProfit)} <span class="${formatPercentageClass(portfolioData.totalReturn)}">${portfolioData.totalReturn} %</span></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h6 class="card-title text-primary">Today's Return (%)</h6>
                                                    <h5>PKR ${portfolioData.todaysReturn} <span class="badge bg-success">(0.60%)</span></h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h6 class="card-title text-primary" title="Includes Capital Gain & Loss + Dividends">Total Return (%)</h6>
                                                    <h5><span class="${getProfitLossClass(portfolioData.unrealizedProfit)} mx-1"></span>PKR ${formatNumber(portfolioData.unrealizedProfit)} <span class="${formatPercentageClass(portfolioData.totalReturn)}">${portfolioData.totalReturn} %</span></h5>
                                                </div>
                                            </div>
                                        </div>
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
                                        <div class="col-md-4">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h6 class="card-title text-primary">Realized P/L</h6>
                                                    <h5>PKR ${formatNumber(portfolioData.realizedProfit)}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

                            $('#portfolio-stats-content').html(componentHtml);
                            $('#portfolio-stats-loader').hide();
                            $('#portfolio-stats-content').show();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading portfolio stats:', error, xhr.responseJSON);
                            $('#portfolio-stats-loader').hide();
                            $('#portfolio-stats-content').html(`
                                <div class="alert alert-danger text-center">
                                    <i data-feather="alert-circle" class="me-2"></i>
                                    <strong>Failed to load portfolio statistics.</strong> Please try again.
                                </div>
                            `).show();
                            feather.replace();
                        }
                    });
                }

                // Helper functions (these should match your PHP helper functions)
                function formatNumber(number) {
                    if (number >= 1000000000) {
                        return (number / 1000000000).toFixed(2) + 'B';
                    } else if (number >= 1000000) {
                        return (number / 1000000).toFixed(2) + 'M';
                    } else if (number >= 1000) {
                        return (number / 1000).toFixed(2) + 'K';
                    }
                    return number.toFixed(2);
                }

                function getProfitLossClass(value) {
                    if (value > 0) return 'text-success';
                    if (value < 0) return 'text-danger';
                    return 'text-muted';
                }

                function formatPercentageClass(value) {
                    if (value > 0) return 'text-success';
                    if (value < 0) return 'text-danger';
                    return 'text-muted';
                }
            });
        </script>
    @endpush
@endsection
