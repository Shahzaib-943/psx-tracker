<div class="container mt-5">
@section('page-title', 'MFProfit Calc')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm p-4">
                    <h4 class="mb-4 text-center">Mutual Funds Profit Calculator</h4>

                    <div class="mb-3">
                        <label class="form-label">Amount Invested</label>
                        <input type="number" wire:model.lazy="amountInvested" class="form-control" placeholder="200000">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Buy NAV</label>
                        <input type="number" step="0.01" wire:model.lazy="buyNav" class="form-control" placeholder="52.2">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current NAV</label>
                        <input type="number" step="0.01" wire:model.lazy="currentNav" class="form-control" placeholder="54.04">
                    </div>

                    <div wire:loading class="mt-2 text-center">
                        <img src="https://api.iconify.design/svg-spinners:blocks-shuffle-3.svg?color=%23570be5" alt="">
                    </div>

                    @if($profit != 0)
                        <div wire:loading.class="d-none" class="alert alert-success mt-3">
                            <strong>Profit :</strong> Rs. {{ number_format($profit, 2) }} ({{ number_format($profitPercent, 2) }}%) <br>
                            <strong>Net Amount :</strong> Rs. {{ number_format($netAmount, 2) }}
                        </div>
                    @endif
                </div>
        </div>
    </div>
</div>
