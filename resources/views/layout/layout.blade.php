<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css', 'resources/js/app.js')
    <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
    <title>Smarthome - IoT</title>
</head>
<body>
    <main class="min-h-screen max-sm:px-8 bg-[#edf2f7] max-sm:pb-30">
        @include('layout.header')
        @include('layout.navbar')
        @include('partials.loader')
        <div class="sm:flex">
            @include('layout.sidebar')
            @include('partials.modalLogout')
            @yield('content')
        </div>
    </main>
    @stack('javascript')
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/js/editProfile.js') }}"></script>
<script src="{{ asset('assets/js/editConection.js') }}"></script>
<script src="{{ asset('assets/js/editPassword.js') }}"></script>
<script src="{{ asset('assets/js/logout.js') }}"></script>