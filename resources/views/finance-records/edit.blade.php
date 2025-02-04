@extends('layouts.app')
@section('page-title', 'Edit Finance Record')
@section('main-page', 'Finance Records')
@section('sub-page', 'Edit')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Finance Record</h4>
                <form id="createFinanceRecordForm" action="{{ route('finance-records.update', $financeRecord->id) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <!-- Finance Type -->
                            <div class="mb-3">
                                <label for="finance_type_id" class="form-label">Finance Type<span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('finance_type_id') is-invalid @enderror"
                                    name="finance_type_id" id="finance_type_id">
                                    <option selected disabled>--- Select Finance Type ---</option>
                                    @foreach ($financeTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('finance_type_id', $financeRecord->financeCategory->finance_type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('finance_type_id')
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
                                <label for="finance_category_id" class="form-label">Finance Category<span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('finance_category_id') is-invalid @enderror"
                                    name="finance_category_id" id="finance_category_id">
                                    <option selected disabled>--- Select Category ---</option>
                                    @foreach ($filteredCategories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('finance_category_id', $financeRecord->finance_category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('finance_category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Amount Input -->
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount<span class="text-danger"> *</span></label>
                                <input id="amount" class="form-control @error('amount') is-invalid @enderror"
                                    name="amount" type="number" step="0.01"
                                    value="{{ old('amount', $financeRecord->amount) }}" placeholder="Enter amount">
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Transaction Date -->
                            <div class="mb-3">
                                <label for="date" class="form-label">Transaction Date<span class="text-danger">
                                        *</span></label>
                                <input id="date" class="form-control @error('date') is-invalid @enderror"
                                    name="date" type="date" value="{{ old('date', $financeRecord->date) }}">
                                @error('date')
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
                            placeholder="Enter a short description">{{ old('description', $financeRecord->description) }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <input class="btn btn-primary" type="submit" value="Update">
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('js/validation.js') }}"></script>
        <script src="{{ asset('nobleui/assets/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
        <script>
            // Populate categories based on selected finance type
            $('#finance_type_id').on('change', function() {
                const financeTypeId = $(this).val();
                const categoryDropdown = $('#finance_category_id');

                if (!financeTypeId) {
                    categoryDropdown.html('<option selected disabled>--- Select Category ---</option>');
                    return;
                }

                // Fetch categories via AJAX
                $.ajax({
                    url: "{{ route('finance-categories.by-type') }}",
                    type: "GET",
                    data: {
                        finance_type_id: financeTypeId
                    },
                    dataType: "json",
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
                    error: function() {
                        alert('Failed to fetch categories. Please try again.');
                    }
                });
            });
        </script>
    @endpush
@endsection
