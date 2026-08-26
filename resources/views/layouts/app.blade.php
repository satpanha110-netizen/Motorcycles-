<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MotoMarket') — ហាងលក់ម៉ូតូ រិទ្ធស្រីដា</title>

    {{-- Bootstrap 5 + Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Custom theme (versioned to bust browser cache) --}}
    <link href="{{ asset('assets/css/app.css') }}?v={{ filemtime(public_path('assets/css/app.css')) }}" rel="stylesheet">

    @stack('styles')
</head>
<body class="has-fixed-nav">

    @include('layouts.partials.navbar')

    <main>
        {{-- Flash messages --}}
        <div class="container mt-3">
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif
            @if(session('error'))
                <x-alert type="danger" :message="session('error')" />
            @endif
            @if($errors->any())
                <x-alert type="danger" message="Please fix the errors below and try again." />
            @endif
        </div>

        @yield('content')
    </main>

    @include('layouts.partials.footer')

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/app.js') }}?v={{ filemtime(public_path('assets/js/app.js')) }}"></script>
    @stack('scripts')
</body>
</html>
