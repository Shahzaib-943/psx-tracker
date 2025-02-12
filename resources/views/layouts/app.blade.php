<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description"
        content="Comprehensive hostel management system designed for hostel owners and managers in Pakistan. Streamline operations, enhance efficiency, and improve tenant satisfaction with our user-friendly platform.">
    <meta name="author" content="Obsidian">
    <meta name="keywords"
        content="hostel management, hostel software, hostel management system, hostel owners, hostel managers, Pakistan, tenant management, room booking, hostel administration, property management, Obsidian">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title', 'Page') | {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- End fonts -->

    <!-- core:css -->
    <link rel="stylesheet" href="{{ asset('nobleui/assets/vendors/core/core.css') }}">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('nobleui/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css') }}">
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('nobleui/assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('nobleui/assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('nobleui/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('nobleui/assets/css/demo1/style.css') }}">
    <!-- End layout styles -->

    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- End Custom styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <link rel="shortcut icon" href="{{ asset('nobleui/assets/images/favicon.png') }}" />
    @stack('style')
</head>

<body>

    <div class="main-wrapper">
        <!-- partial:partials/_sidebar.html -->
        @include('layouts.sidebar')
        <!-- partial -->

        <div class="page-wrapper">

            <!-- partial:partials/_navbar.html -->
            @include('layouts.header')
            <!-- partial -->

            <div class="page-content">
                <nav class="page-breadcrumb">
                    <ol class="breadcrumb">
                        @if (View::hasSection('main-page'))
                            <li class="breadcrumb-item active">@yield('main-page')</li>
                        @endif

                        @if (View::hasSection('sub-page'))
                            <li class="breadcrumb-item active">@yield('sub-page')</li>
                        @endif
                    </ol>

                </nav>
                <!-- row -->

                @yield('content')
            </div>

            <!-- partial:partials/_footer.html -->
            <footer
                class="footer d-flex flex-column flex-md-row align-items-center justify-content-between px-4 py-3 border-top small">
                <p class="text-muted mb-1 mb-md-0">Copyright © {{ date('Y') }} <a href="https://obsidianflux.com/"
                        target="_blank">ObsidianFlux</a></p>
                <p class="text-muted">Handcrafted With <i class="mb-1 text-primary ms-1 icon-sm"
                        data-feather="heart"></i> From <a href="https://obsidianflux.com/"
                        target="_blank">ObsidianFlux</a></p>
            </footer>
            <!-- partial -->

        </div>
    </div>

    <script src="{{ asset('nobleui/assets/vendors/core/core.js') }}"></script>
    {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}
    @stack('scripts')
    <script>
        window.appConfig = {
            userRole: @json(auth()->user()->getRoleNames()->first()),
            ADMIN: @json(\App\Constants\AppConstant::ROLE_ADMIN),
        };
    </script>
    <!-- inject:js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('nobleui/assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('nobleui/assets/js/template.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

</body>

</html>
