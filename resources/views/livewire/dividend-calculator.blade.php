<div class="container mt-5">
@section('page-title', 'Dividend Calc')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm p-4">
                    <h4 class="mb-4 text-center">Dividend Calculator</h4>

                    <div class="mb-3">
                        <label class="form-label">Number of Shares</label>
                        <input type="number" wire:model.lazy="shares" class="form-control" placeholder="e.g., 100">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Dividend per Share</label>
                        <input type="number" step="0.01" wire:model.lazy="dividendPerShare" class="form-control" placeholder="e.g., 3.5">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tax (%)</label>
                        <input type="number" step="0.01" wire:model.lazy="tax" class="form-control" placeholder="Default is 15%">
                    </div>

                    <div wire:loading class="mt-2 text-center">
                        <img src="https://api.iconify.design/svg-spinners:blocks-shuffle-3.svg?color=%23570be5" alt="">
                    </div>

                    @if($netDividend != 0)
                        <div class="alert alert-success mt-3">
                            <strong>Net Dividend:</strong> Rs. {{ number_format($netDividend, 2) }}
                        </div>
                    @endif
                </div>
        </div>
    </div>
</div>
