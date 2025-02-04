@extends('layouts.guest')
@section('page-title', 'Login')
@section('content')
    <div class="main-wrapper">
        <div class="page-wrapper full-page">
            <div class="page-content d-flex align-items-center justify-content-center">

                <div class="row w-100 mx-0 auth-page">
                    <div class="col-md-8 col-xl-6 mx-auto">
                        <div class="card">
                            <div class="row">
                                <div class="col-md-12 ps-md-0">
                                    <div class="auth-form-wrapper px-4 py-5">
                                        <a href="{{ route('home') }}"
                                            class="noble-ui-logo d-block mb-2">Fiscal<span>Ease</span></a>
                                        <h5 class="text-muted fw-normal mb-4">Welcome to {{ config('app.name') }}! 👋</h5>
                                        <h5 class="text-muted fw-normal mb-4">Sign in to your account and take control of your finances today!</h5>
                                        <form id="loginForm" class="forms-sample" method="POST"
                                            action="{{ route('login') }}">
                                            @csrf

                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email address</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                                    name="email" value="{{ old('email') }}" required autocomplete="email"
                                                    autofocus placeholder="Email">
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" name="password" required autocomplete="current-password"
                                                    placeholder="Password">
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" id="remember"
                                                    name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="remember">
                                                    Remember me
                                                </label>
                                            </div>

                                            <div class="d-flex">
                                                <button type="submit"
                                                    class="btn btn-primary me-2 mb-2 mb-md-0 text-white">Login</button>
                                                <a href="{{ route('auth.login-page', ['driver' => 'google']) }}" type="button"
                                                    class="btn btn-outline-primary btn-icon-text mx-2 mb-2 mb-md-0">
                                                    <i class="btn-icon-prepend" data-feather="chrome"></i>
                                                    Login with Google
                                                </a>

                                                @if (Route::has('password.request'))
                                                    <div class="mt-3">
                                                        <a class="text-muted" href="{{ route('password.request') }}">
                                                            Forgot Your Password?
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>

                                            <div>
                                                @if (Route::has('register'))
                                                    <a href="{{ route('register') }}" class="d-block mt-3 text-muted">Not a
                                                        user? Sign up</a>
                                                @endif
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('js/validation.js') }}"></script>
        <script src="{{ asset('nobleui/assets/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
    @endpush

@endsection
