<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @vite('resources/css/app.css')
  <title>@yield('title')</title>
</head>

<body class="bg-gray-200">
  
  {{-- navbar --}}
  @include('layouts.nav-bar')

  {{-- load-page --}}
  <div id="content" class="flex ml-32 max-w-6xl mx-auto">
    {{-- Only include $slot if it's defined --}}
    @isset($slot)
        {{ $slot }}
    @endisset
  </div>
  
  
</body>
</html>