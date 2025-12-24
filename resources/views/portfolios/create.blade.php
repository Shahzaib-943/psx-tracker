@extends('layouts.app')
@section('page-title', 'Create Portfolio')
@section('main-page', 'Portfolios')
@section('sub-page', 'Create')
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
            <h4 class="card-title">Create Portfolio</h4>
            <form id="createPortfolioForm" action="{{ route('portfolios.store') }}" method="POST">
                @csrf
                <!-- Name Input -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name<span class="text-danger"> *</span></label>
                    <input id="name" class="form-control @error('name') is-invalid @enderror" name="name" type="text"
                        value="{{ old('name') }}" placeholder="Enter portfolio name">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" class="form-control @error('description') is-invalid @enderror"
                        name="description" placeholder="Enter a short description">{{ old('description') }}</textarea>
                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <!-- User Selection (for admin) -->
                @role(\App\Constants\AppConstant::ROLE_ADMIN)
                <div class="mb-3">
                    <label for="user_id" class="form-label">User</label>
                    <select class="form-select @error('user_id') is-invalid @enderror" name="user_id" id="user_id">
                        <option value="" selected>--- Select User ---</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                @endrole


        </div>
        <div class="card-body">
            <h4 class="card-title">Add Stock To Portfolio</h4>
            <div class="row">

                <!-- Right Column -->
                <div class="col-md-6">
                    <!-- Finance Category -->
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock<span class="text-danger">*</span></label>
                        <select class="form-select @error('stock') is-invalid @enderror" name="stock" id="stock">
                            <option value="" {{ old('stock')=='' || old('stock')==null ? 'selected' : '' }} disabled>---
                                Select Stock ---</option>
                            @foreach ($stocks as $stock)
                            <option value="{{ $stock->slug }}" {{ old('stock')==$stock->slug ? 'selected' : '' }}>
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
                <div class="col-md-6">
                    <!-- Amount Input -->
                    <div class="mb-3">
                        <label for="price_per_share" class="form-label">Price<span class="text-danger">
                                *</span></label>
                        <input id="price_per_share" class="form-control @error('price_per_share') is-invalid @enderror"
                            name="price_per_share" type="number" step="0.01" value="{{ old('price_per_share') }}"
                            placeholder="Enter price per share">
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

                <div class="col-md-6">
                    <!-- Transaction transaction_date -->
                    <div class="mb-3">
                        <label for="transaction_date" class="form-label">Transaction Date<span class="text-danger">
                                *</span></label>
                        <input id="transaction_date"
                            class="form-control @error('transaction_date') is-invalid @enderror" name="transaction_date"
                            type="date" value="{{ old('transaction_date', now()->format('Y-m-d')) }}">
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
                                name="make_deductions" type="checkbox" value="1" {{ old('make_deductions') ? 'checked'
                                : '' }}>
                            <label for="make_deductions" class="form-check-label">Make Deductions</label>
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
                <textarea id="description" class="form-control @error('description') is-invalid @enderror"
                    name="description" placeholder="Enter a short description">{{ old('description') }}</textarea>
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
@endpush
@endsection