@extends('layouts.app')
@section('page-title', 'Edit Financial Category')
@section('main-page', 'Financial Category')
@section('sub-page', 'Edit')
@section('content')
    <style>
        .color-input {
            width: 50px;
            height: 30px;
            padding: 0;
        }
    </style>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Financial Category</h4>
                <form id="editFinanceCategoryForm" action="{{ route('finance-categories.update', $financeCategory->slug) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Name Input -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name<span class="text-danger"> *</span></label>
                        <input id="name" class="form-control @error('name') is-invalid @enderror" name="name"
                            type="text" value="{{ old('name', $financeCategory->name) }}"
                            placeholder="Enter category name">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Finance Type Dropdown -->
                    <div class="mb-3">
                        <label for="finance_type_id" class="form-label">Type<span class="text-danger"> *</span></label>
                        <select class="form-select @error('finance_type_id') is-invalid @enderror" name="finance_type_id"
                            id="finance_type_id">
                            <option selected disabled>--- Select Type ---</option>
                            @foreach ($financeTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ old('finance_type_id', $financeCategory->finance_type_id) == $type->id ? 'selected' : '' }}>
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

                    <!-- Color Picker -->
                    <div class="mb-3">
                        <label for="color" class="form-label">Category Color
                            <span class="text-danger"> *</span>
                            <span class="tooltip-hover" title="This color will be used in charts">[?]</span>
                        </label>
                        <input id="color" class="form-control color-input @error('color') is-invalid @enderror"
                            name="color" type="color" value="{{ old('color', $financeCategory->color) }}">
                        @error('color')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- User Selection (for admin) -->
                    @role(\App\Models\User::ROLE_ADMIN)
                        <div class="mb-3">
                            <input type="checkbox" name="is_common" id="is_common"
                                {{ $financeCategory->is_common ? 'checked' : '' }}>
                            <label for="is_common" class="form-label">Is Common Category<span class="text-danger">
                                    *</span></label>
                            @error('is_common')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Assign to User<span class="text-danger"> *</span></label>
                            <select class="form-select @error('user_id') is-invalid @enderror" name="user_id" id="user_id">
                                <option value="" >--- Select User ---</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('user_id', $financeCategory->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @endrole

                    <!-- Submit Button -->
                    <input class="btn btn-primary" type="submit" value="Update">
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            window.appConfig = {
                userRole: @json(auth()->user()->getRoleNames()->first()),
                ADMIN: @json(\App\Models\User::ROLE_ADMIN),
            };
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const isCommonCheckbox = document.getElementById('is_common');
                const userSelectDropdown = document.getElementById('user_id');

                // Function to toggle the dropdown's disabled state
                const toggleDropdown = () => {
                    if (isCommonCheckbox.checked) {
                        userSelectDropdown.disabled = true;
                    } else {
                        userSelectDropdown.disabled = false;
                    }
                };

                // Function to toggle the checkbox's disabled state
                const toggleCheckbox = () => {
                    isCommonCheckbox.disabled = userSelectDropdown.value !== '';
                    if (userSelectDropdown.value === '') {
                        isCommonCheckbox.disabled = false; // Enable checkbox if no option is selected
                    }
                };

                // Attach event listener to the checkbox
                isCommonCheckbox.addEventListener('change', toggleDropdown);

                // Attach event listener to the dropdown
                userSelectDropdown.addEventListener('change', toggleCheckbox);

                // Initialize the states on page load
                toggleDropdown();
            });
        </script>
        <script src="{{ asset('js/validation.js') }}"></script>
        <script src="{{ asset('nobleui/assets/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
    @endpush
@endsection
