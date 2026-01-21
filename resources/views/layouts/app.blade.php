<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام السيرة الذاتية المتقدم - CV Registration System')</title>
    
    {{-- External Stylesheets --}}
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    {{-- Register Page Styles --}}
    @if(request()->routeIs('register*'))
        <link rel="stylesheet" href="{{ asset('/css/register.css') }}">
    @endif
    
    {{-- Additional Styles --}}
    @stack('styles')
</head>
<body>
    {{-- Header Component --}}
    @include('components.header.header')
    
    {{-- Main Content --}}
    @yield('content')
    
    {{-- Back to Top Button --}}
    @include('components.back-to-top')
    
    {{-- Register Page Scripts --}}
    @if(request()->routeIs('register*'))
        <script src="{{ asset('/js/register.js') }}"></script>
    @endif
    
    {{-- Additional Scripts --}}
    @stack('scripts')
</body>
</html>

