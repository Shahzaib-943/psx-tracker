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

<!-- Transaction Modal (Buy/Sell) -->
<div class="modal fade" id="transactionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="transactionModalTitle">Buy Stock</h5>
                    <span class="badge bg-primary fs-6 mt-1" id="transaction_symbol_badge"></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="transactionForm">
                <div class="modal-body">
                    <input type="hidden" id="transaction_type" name="type">
                    <input type="hidden" id="holding_id" name="holding_id">
                    <input type="hidden" id="portfolio_id" name="portfolio_id" value="{{ $portfolio->public_id }}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="0.001"
                                step="0.001" required>
                            <small class="text-muted" id="available_qty"></small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price per Share <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="price" name="price" min="0.01" step="0.01"
                                required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Transaction Date</label>
                        <input type="date" class="form-control" id="transaction_date" name="transaction_date"
                            value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                    </div>

                    <div class="alert alert-info mb-0">
                        <strong>Total Amount:</strong> <span id="total_amount">$0.00</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" id="submitTransaction">
                        <span class="spinner-border spinner-border-sm d-none" id="transaction-spinner"></span>
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Dividend Modal -->
<div class="modal fade" id="dividendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">Add Dividend</h5>
                    <span class="badge bg-success fs-6 mt-1" id="dividend_symbol_badge"></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="dividendForm">
                <div class="modal-body">
                    <input type="hidden" id="dividend_holding_id" name="holding_id">
                    <input type="hidden" id="dividend_portfolio_id" name="portfolio_id" value="{{ $portfolio->public_id }}">
                    <input type="hidden" id="dividend_shares_held" name="shares_held">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dividend per Share <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="dividend_per_share" name="dividend_per_share"
                                min="0.001" step="0.001" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Payment Date</label>
                            <input type="date" class="form-control" id="dividend_date" name="dividend_date"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <!-- Shares held info display -->
                    <div class="alert alert-secondary py-2 mb-3">
                        <small>Shares Held: <strong id="dividend_shares_display">0</strong></small>
                    </div>

                    <!-- WHT Checkbox -->
                    <div class="mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="deduct_wht" name="deduct_wht">
                            <label class="form-check-label" for="deduct_wht">
                                Deduct Withholding Tax (WHT)
                            </label>
                        </div>
                    </div>

                    <!-- WHT Input — hidden by default -->
                    <div id="wht_field" class="mb-3" style="display: none;">
                        <label class="form-label">WHT Rate (%)</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="wht_rate" name="wht_rate"
                                min="0" max="100" step="0.01" value="15">
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted">WHT Amount: <strong id="wht_amount">Rs 0.00</strong></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="dividend_notes" name="notes" rows="2"></textarea>
                    </div>

                    <!-- Summary -->
                    <div class="alert alert-success mb-0">
                        <div class="d-flex justify-content-between">
                            <span>Gross Dividend:</span>
                            <strong id="gross_dividend">Rs 0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between" id="wht_summary_row" style="display: none !important;">
                            <span class="text-danger">WHT Deducted:</span>
                            <strong class="text-danger" id="wht_summary_amount">- Rs 0.00</strong>
                        </div>
                        <hr class="my-1">
                        <div class="d-flex justify-content-between">
                            <span>Net Dividend:</span>
                            <strong id="total_dividend">Rs 0.00</strong>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="submitDividend">
                        <span class="spinner-border spinner-border-sm d-none" id="dividend-spinner"></span>
                        Add Dividend
                    </button>
                </div>
            </form>
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
<script>
    let currentHoldingQuantity = 0;

    function openTransactionModal(type, symbol, holdingId, quantity) {
        const form = document.getElementById('transactionForm');
        form.reset();

        document.getElementById('transaction_type').value = type;
        document.getElementById('holding_id').value = holdingId;
        document.getElementById('transaction_symbol_badge').textContent = symbol;

        const isBuy = type === 'buy';
        document.getElementById('transactionModalTitle').textContent = isBuy ? 'Buy More Stock' : 'Sell Stock';

        const submitBtn = document.getElementById('submitTransaction');
        submitBtn.className = 'btn ' + (isBuy ? 'btn-success' : 'btn-danger');
        submitBtn.innerHTML = isBuy ? 'Confirm Purchase' : 'Confirm Sale';

        if (!isBuy) {
            currentHoldingQuantity = quantity;
            document.getElementById('available_qty').textContent = `Available: ${quantity} shares`;
            document.getElementById('quantity').max = quantity;
        } else {
            currentHoldingQuantity = 0;
            document.getElementById('available_qty').textContent = '';
            document.getElementById('quantity').removeAttribute('max');
        }

        document.getElementById('total_amount').textContent = 'Rs 0.00';
        new bootstrap.Modal(document.getElementById('transactionModal')).show();
    }

    function openDividendModal(symbol, holdingId, quantity) {
        document.getElementById('dividendForm').reset();

        document.getElementById('dividend_holding_id').value = holdingId;
        document.getElementById('dividend_shares_held').value = quantity;
        document.getElementById('dividend_symbol_badge').textContent = symbol;
console.log("id: ", holdingId, " quantity: ", quantity)
        currentHoldingQuantity = quantity;
        document.getElementById('dividend_shares_display').textContent = quantity;

        // Reset WHT state
        $('#deduct_wht').prop('checked', false);
        $('#wht_field').hide();
        $('#wht_summary_row').hide();
        $('#wht_rate').val(15); // Reset to default 15%

        // Reset totals
        $('#gross_dividend').text('Rs 0.00');
        $('#wht_amount').text('Rs 0.00');
        $('#wht_summary_amount').text('- Rs 0.00');
        $('#total_dividend').text('Rs 0.00');

        new bootstrap.Modal(document.getElementById('dividendModal')).show();
    }

// Toggle WHT field
$('#deduct_wht').on('change', function() {
    if ($(this).is(':checked')) {
        $('#wht_field').show();
        $('#wht_summary_row').show();
    } else {
        $('#wht_field').hide();
        $('#wht_summary_row').hide();
    }
    calculateDividend();
});

// Recalculate when WHT rate changes
$('#wht_rate').on('input', calculateDividend);

// Recalculate when dividend per share changes
$('#dividend_per_share').on('input', calculateDividend);

function calculateDividend() {
    const perShare = parseFloat($('#dividend_per_share').val()) || 0;
    const gross = perShare * currentHoldingQuantity;
    const whtChecked = $('#deduct_wht').is(':checked');
    const whtRate = whtChecked ? (parseFloat($('#wht_rate').val()) || 0) : 0;

    const whtAmount = gross * (whtRate / 100);
    const net = gross - whtAmount;

    $('#gross_dividend').text('RS ' + gross.toFixed(2));
    $('#wht_amount').text('RS ' + whtAmount.toFixed(2));
    $('#wht_summary_amount').text('- RS ' + whtAmount.toFixed(2));
    $('#total_dividend').text('RS ' + net.toFixed(2));
}

    // Calculate total amount for buy/sell
    $('#quantity, #price').on('input', function() {
        const qty = parseFloat($('#quantity').val()) || 0;
        const price = parseFloat($('#price').val()) || 0;
        $('#total_amount').text('Rs ' + (qty * price).toFixed(2));

        // Validate sell quantity
        if ($('#transaction_type').val() === 'sell' && qty > currentHoldingQuantity) {
            $('#quantity').addClass('is-invalid');
            $('#submitTransaction').prop('disabled', true);
        } else {
            $('#quantity').removeClass('is-invalid');
            $('#submitTransaction').prop('disabled', false);
        }
    });

    // Calculate total dividend
    $('#dividend_per_share').on('input', function() {
        const perShare = parseFloat($(this).val()) || 0;
        $('#total_dividend').text('Rs ' + (perShare * currentHoldingQuantity).toFixed(2));
    });

    // Submit buy/sell transaction
    $('#transactionForm').on('submit', function(e) {
        e.preventDefault();

        const submitBtn = $('#submitTransaction');
        const spinner = $('#transaction-spinner');

        submitBtn.prop('disabled', true);
        spinner.removeClass('d-none');

        $.ajax({
            
            type: 'POST',
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                bootstrap.Modal.getInstance(document.getElementById('transactionModal')).hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message || 'Transaction completed successfully',
                    timer: 2000,
                    showConfirmButton: false
                });
                $('#event-types_dataTable').DataTable().ajax.reload();
                loadPortfolioStats();
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to process transaction'
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                spinner.addClass('d-none');
            }
        });
    });

    // Submit dividend
    $('#dividendForm').on('submit', function(e) {
        e.preventDefault();

        const submitBtn = $('#submitDividend');
        const spinner = $('#dividend-spinner');

        submitBtn.prop('disabled', true);
        spinner.removeClass('d-none');

        $.ajax({
            url: '{{ route("dividends.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                bootstrap.Modal.getInstance(document.getElementById('dividendModal')).hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message || 'Dividend added successfully',
                    timer: 2000,
                    showConfirmButton: false
                });
                loadPortfolioStats();
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to add dividend'
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                spinner.addClass('d-none');
            }
        });
    });

    // Reload portfolio stats card
    function loadPortfolioStats() {
        $.ajax({
            url: '{{ route("portfolios.stats") }}',
            type: 'GET',
            data: {
                portfolio_id: @json($portfolio->public_id),
                format: 'html'
            },
            success: function(response) {
                $('#portfolio-show-content').html(response.html);
                feather.replace();
            }
        });
    }
</script>

@push('scripts')
<script src="{{ asset('nobleui/assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('nobleui/assets/js/data-table.js') }}"></script>
<script src="{{ asset('nobleui/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush
@endsection