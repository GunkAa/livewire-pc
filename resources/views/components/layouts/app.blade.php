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
  @include('components.layouts.nav-bar')

  {{-- Load page based on route --}}
  <div>
    {{-- Only include $slot if it's defined --}}
    @isset($slot)
        {{ $slot }}
    @endisset
  </div>



</body>
</html>