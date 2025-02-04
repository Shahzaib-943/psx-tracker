@extends('layouts.app')
@section('page-title', 'User Profile')
@section('main-page', 'User Profile')
{{-- @section('sub-page', 'List') --}}
@section('content')
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">User Profile</h6>

                <form class="forms-sample">
                    <div class="mb-3">
                        <label for="exampleInputUsername1" class="form-label">Username</label>
                        <input type="text" class="form-control" id="exampleInputUsername1" autocomplete="off"
                            placeholder="Username" value=" {{ $profile->name }} ">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email" value="{{ $profile->email }}">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" autocomplete="off"
                            placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                </form>

            </div>
        </div>
    </div>
@endsection
