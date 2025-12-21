@extends('layouts.app')
@section('page-title', 'System Settings')
@section('main-page', 'System Settings')
{{-- @section('sub-page', 'List') --}}
@section('content')
<div class="grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">System Settings</h6>

            <form class="forms-sample" action="{{ route('system-settings.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="exampleInputUsername1" class="form-label">Market Opening Time</label>
                        <input type="time" class="form-control" id="market_opening_time" name="market_opening_time"
                            autocomplete="off" placeholder="Market Opening Time" value="{{ $marketOpeningTime ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="exampleInputUsername1" class="form-label">Market Closing Time</label>
                        <input type="time" class="form-control" id="market_closing_time" name="market_closing_time"
                            autocomplete="off" placeholder="Market Closing Time" value="{{ $marketClosingTime ?? '' }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
            </form>

        </div>
    </div>
</div>
@endsection