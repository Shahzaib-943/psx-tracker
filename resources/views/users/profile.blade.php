@extends('layouts.app')
@section('page-title', 'User Profile')
@section('main-page', 'User Profile')
{{-- @section('sub-page', 'List') --}}
@section('content')
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">User Profile</h6>

                <form class="forms-sample" id="updateProfileForm" action="{{ route('profile.update', $profile->public_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="exampleInputUsername1" class="form-label">Username</label>
                        <input type="text" class="form-control" id="exampleInputUsername1" name="name" autocomplete="off"
                            placeholder="Username" value=" {{ $profile->name }} ">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" name="email" readonly placeholder="Email" value="{{ $profile->email }}">
                    </div>
                    <div class="mb-3">
                        <label for="update_password" class="form-label">Update Password</label>
                        <input type="password" class="form-control" id="update_password" name="password" autocomplete="off"
                            placeholder="Update Password">
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="password_confirmation" autocomplete="off"
                            placeholder="Confirm Password">
                    </div>
                    <input class="btn btn-primary" type="submit" value="Update">
                </form>

            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('js/validation.js') }}"></script>
        <script src="{{ asset('nobleui/assets/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
    @endpush
@endsection
