<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @if(request()->routeIs('login'))
    <title>ShopARA-Login</title>
@elseif(request()->routeIs('register'))
    <title>ShopARA-Register</title>
@elseif(request()->routeIs('password.request'))
    <title>ShopARA-Forgot Password</title>
@elseif(request()->routeIs('password.reset'))
    <title>ShopARA-Reset Password</title>
@elseif(request()->routeIs('verification.notice'))
    <title>ShopARA-Verify Email</title>
@elseif(request()->routeIs('password.confirm'))
    <title>ShopARA-Confirm Password</title>
@else
    <title>{{ config('app.name', 'ShopARA') }}</title>
@endif

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/" wire:navigate>
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
