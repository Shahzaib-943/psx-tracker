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
                            <img src="https://api.iconify.design/svg-spinners:blocks-shuffle-3.svg?color=%23570be5" alt="">
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
                            portfolio_id: portfolioId,
                            format: 'html'
                        },
                        success: function(response) {
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

                            // If HTML is returned (component), render it directly
                            if (response.html) {
                                $('#portfolio-stats-loader').hide();
                                $('#portfolio-stats-content').html(response.html).show();
                                feather.replace();
                                return;
                            }

                            // Fallback: if html missing but data present, show generic message
                            $('#portfolio-stats-loader').hide();
                            $('#portfolio-stats-content').html(`
                                <div class="alert alert-info text-center">
                                    <i data-feather="info" class="me-2"></i>
                                    <strong>No portfolio data available.</strong>
                                </div>
                            `).show();
                            feather.replace();
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
            });
        </script>
    @endpush
@endsection
