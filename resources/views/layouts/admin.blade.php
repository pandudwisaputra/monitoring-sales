<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel')</title>

    {{-- Font Awesome --}}
    <link href="{{ asset('vendor/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    {{-- SB Admin 2 --}}
    <link href="{{ asset('vendor/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --bs-primary: #010064;
            --bs-primary-rgb: 1, 0, 100;
        }

        .bg-gradient-primary {
            background-color: #010064 !important;
            background-image: linear-gradient(180deg, #010064 10%, #02004f 100%) !important;
        }

        .sidebar.bg-gradient-primary,
        .sidebar-dark .nav-item.active .nav-link,
        .sidebar-dark .nav-item .nav-link:hover,
        .sidebar-dark .nav-item .nav-link:focus {
            background-color: #010064 !important;
        }

        .text-primary,
        .card.border-left-primary .text-primary,
        .border-left-primary {
            color: #010064 !important;
        }

        .border-left-primary {
            border-left: .25rem solid #010064 !important;
        }

        .btn-primary,
        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: #010064 !important;
            border-color: #010064 !important;
            box-shadow: none !important;
        }

        .card-header.text-primary {
            color: #010064 !important;
        }
    </style>

    @stack('styles')
</head>

<body id="page-top">
    <div id="wrapper">

        {{-- Sidebar --}}
        @include('layouts.partials.sidebar')

        {{-- Content Wrapper --}}
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                {{-- Topbar --}}
                @include('layouts.partials.topbar')

                {{-- Main Content --}}
                <div class="container-fluid">
                    @yield('content')
                </div>

            </div>

            {{-- Footer --}}
            @include('layouts.partials.footer')
        </div>
    </div>

    {{-- Scroll to Top --}}
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    {{-- Scripts --}}
    <script src="{{ asset('vendor/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('vendor/js/sb-admin-2.min.js') }}"></script>

    @stack('scripts')
</body>
</html>