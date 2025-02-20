@extends('layouts.app')
@section('page-title', 'Add Stock To Portfolio')
@section('main-page', 'Portfolios')
@section('sub-page', 'Add Stock')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Stock To Portfolio</h4>
                <form id="createStockTradeForm" action="{{ route('portfolio-holdings.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <!-- Portfolio -->
                            <div class="mb-3">
                                <label for="portfolio" class="form-label">Portfolio<span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('portfolio') is-invalid @enderror" name="portfolio"
                                    id="portfolio">
                                    <option selected disabled>--- Select Portfolio ---</option>
                                    @foreach ($portfolios as $portfolio)
                                        <option value="{{ $portfolio->slug }}"
                                            {{ old('portfolio') == $portfolio->slug ? 'selected' : '' }}>
                                            {{ $portfolio->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('portfolio')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <!-- Finance Category -->
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock<span class="text-danger">*</span></label>
                                <select class="form-select @error('stock') is-invalid @enderror" name="stock"
                                    id="stock">
                                    <option selected disabled>--- Select Stock ---</option>
                                    @foreach ($stocks as $stock)
                                        <option value="{{ $stock->slug }}"
                                            {{ old('stock') == $stock->slug ? 'selected' : '' }}>
                                            {{ $stock->symbol }} | {{ $stock->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('stock')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><!-- Amount Input -->
                            <div class="mb-3">
                                <label for="price_per_share" class="form-label">Price<span class="text-danger">
                                        *</span></label>
                                <input id="price_per_share"
                                    class="form-control @error('price_per_share') is-invalid @enderror"
                                    name="price_per_share" type="number" step="0.01"
                                    value="{{ old('price_per_share') }}" placeholder="Enter price per share">
                                @error('price_per_share')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity<span class="text-danger"> *</span></label>
                                <input id="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                    name="quantity" type="number" value="{{ old('quantity') }}"
                                    placeholder="Enter no of shares">
                                @error('quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-md-6"><!-- Transaction transaction_date -->
                            <div class="mb-3">
                                <label for="transaction_date" class="form-label">Transaction Date<span class="text-danger">
                                        *</span></label>
                                <input id="transaction_date"
                                    class="form-control @error('transaction_date') is-invalid @enderror"
                                    name="transaction_date" type="date"
                                    value="{{ old('transaction_date', now()->format('Y-m-d')) }}">
                                @error('transaction_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mt-4">
                                <div class="form-check">
                                    <input id="make_deductions"
                                        class="form-check-input @error('make_deductions') is-invalid @enderror"
                                        name="make_deductions" type="checkbox" value="1"
                                        {{ old('make_deductions') ? 'checked' : '' }} checked>
                                    <label for="make_deductions" class="form-check-label">Make Deductions<span
                                            class="text-danger"> *</span></label>
                                </div>
                                @error('make_deductions')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description"
                            placeholder="Enter a short description">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <input class="btn btn-primary" type="submit" value="Create">
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('js/validation.js') }}"></script>
        <script src="{{ asset('nobleui/assets/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
        {{-- <script>
            // Populate categories based on selected portfolio
            $('#finance_type_id').on('change', function() {
                const financeTypeId = $(this).val();
                console.log("qqq", financeTypeId);
                const categoryDropdown = $('#stock');

                if (!financeTypeId) {
                    categoryDropdown.html('<option selected disabled>--- Select Finance Category First ---</option>');
                    return;
                }

                // Fetch categories via AJAX
                $.ajax({
                    url: "{{ route('finance-categories.by-type') }}", // Ensure this route exists in your web.php
                    type: "GET",
                    data: {
                        finance_type_id: financeTypeId
                    },
                    dataType: "json", // Specify the data type expected
                    success: function(response) {
                        if (response.length > 0) {
                            let options = '<option selected disabled>--- Select Category ---</option>';
                            response.forEach(category => {
                                options +=
                                    `<option value="${category.id}">${category.name}</option>`;
                            });
                            categoryDropdown.html(options);
                        } else {
                            categoryDropdown.html(
                                '<option selected disabled>No categories available</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching categories:", xhr.responseText);
                        alert(
                            'Failed to fetch categories. Please try again.'
                        ); // Display a user-friendly error
                    }
                });
            });
        </script> --}}
    @endpush
@endsection
