<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title', 'Dashboard') - KasKelas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body>
<div class="kk-app">
    <div class="kk-overlay"></div>
    @include('components.sidebar')

    <div class="kk-main">
        @include('components.navbar')

        <div class="kk-content">
            @if (session('success'))
                <div class="kk-alert kk-alert-success" data-alert>{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="kk-alert kk-alert-danger" data-alert>{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="kk-alert kk-alert-danger" data-alert>
                    <ul style="margin:0; padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
