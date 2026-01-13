<div class="container mt-5">
@section('page-title', 'MFReturn Calc')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm p-4">
                    <h4 class="mb-4 text-center">Mutual Funds Return Calculator</h4>

                    <div class="mb-3">
                        <label class="form-label">Amount Invested</label>
                        <input type="number" wire:model.lazy="amountInvested" class="form-control" placeholder="e.g., 100">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Buy NAV</label>
                        <input type="number" step="0.01" wire:model.lazy="buyNav" class="form-control" placeholder="e.g., 3.5">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current NAV</label>
                        <input type="number" step="0.01" wire:model.lazy="currentNav" class="form-control" placeholder="Default is 15%">
                    </div>

                    <div wire:loading class="mt-2 text-center">
                        <img src="https://api.iconify.design/svg-spinners:blocks-shuffle-3.svg?color=%23570be5" alt="">
                    </div>

                    @if($return != 0)
                        <div wire:loading.class="d-none" class="alert alert-success mt-3">
                            <strong>Return :</strong> Rs. {{ number_format($return, 2) }} ({{ number_format($returnPercent, 2) }}%) <br>
                            <strong>Net Amount :</strong> Rs. {{ number_format($netAmount, 2) }}
                        </div>
                    @endif
                </div>
        </div>
    </div>
</div>
