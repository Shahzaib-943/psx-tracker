@extends('layouts.app')
@section('page-title', 'Create Portfolio')
@section('main-page', 'Portfolio')
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
                        <label for="name" class="form-label">Category Name<span class="text-danger"> *</span></label>
                        <input id="name" class="form-control @error('name') is-invalid @enderror" name="name"
                            type="text" value="{{ old('name') }}" placeholder="Enter category name">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" rows="5" class="form-control @error('description') is-invalid @enderror"
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
