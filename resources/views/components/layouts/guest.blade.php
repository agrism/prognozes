<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'x') }}</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/htmx.org@1.9.12"></script>
</head>

<body hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'>
    <div class="flex flex-col justify-center min-h-screen antialiased bg-gray-100">
        {{ $slot }}
    </div>
</body>

</html>
