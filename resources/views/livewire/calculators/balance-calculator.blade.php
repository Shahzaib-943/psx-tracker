<div class="container mt-5">
@section('page-title', 'Balance Calc')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm p-4">
                    <h4 class="mb-4 text-center">Balance Calculator</h4>

                    <div class="mb-3">
                        <label class="form-label">Current Balance</label>
                        <input type="number" wire:model.lazy="currentBalance" class="form-control" placeholder="10">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Package Price</label>
                        <input type="number" step="0.01" wire:model.lazy="packagePrice" class="form-control" placeholder="517">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tax Rate</label>
                        <input type="number" step="0.01" wire:model.lazy="taxRate" class="form-control" placeholder="54.04">
                    </div>

                    <div wire:loading class="mt-2 text-center">
                        <img src="https://api.iconify.design/svg-spinners:blocks-shuffle-3.svg?color=%23570be5" alt="">
                    </div>

                    @if($requiredBalance != 0)
                        <div wire:loading.class="d-none" class="alert alert-success mt-3">
                                <strong>You need to load :</strong> Rs. {{ number_format($requiredBalance, 2) }} 
                        </div>
                    @endif
                </div>
        </div>
    </div>
</div>
