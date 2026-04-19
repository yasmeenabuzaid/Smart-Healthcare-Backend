<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Smart Healthcare Dashboard') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/vendors/core/core.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">

    @if(app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/css/demo1/style-rtl.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/demo1/style.css') }}">
    @endif

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
</head>
<body>
    <div class="main-wrapper">

        @include('layouts.sections.sidebar')

        <div class="page-wrapper">

            @include('layouts.sections.navbar')

            <div class="page-content">
                @yield('content')
            </div>

            @include('layouts.sections.footer')

        </div>
    </div>

    <script src="{{ asset('assets/vendors/core/core.js') }}"></script>

    <script src="{{ asset('assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>

    @yield('scripts')
</body>
</html>
