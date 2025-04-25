<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Comprehensive hostel management system designed for hostel owners and managers in Pakistan. Streamline operations, enhance efficiency, and improve tenant satisfaction with our user-friendly platform.">
    <meta name="author" content="Obsidian">
    <meta name="keywords" content="hostel management, hostel software, hostel management system, hostel owners, hostel managers, Pakistan, tenant management, room booking, hostel administration, property management, Obsidian">


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
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('nobleui/assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('nobleui/assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('nobleui/assets/css/demo1/style.css') }}">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="{{ asset('nobleui/assets/images/favicon.png') }}" />
    @livewireStyles

</head>
<body>
    {{ $slot }}
    @livewireScripts
</body>
</html>
