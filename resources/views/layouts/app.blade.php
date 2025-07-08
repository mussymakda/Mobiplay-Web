<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
  @include('layouts.meta')
  @include('layouts.style')
  <title>Mobiplay</title>

</head>

<body class="inner-page">
  <div id="wrapper">
    @include('layouts.sidebar')
    @include('layouts.navbar')

    @yield('content')

  </div>

  @include('layouts.script')
</body>

</html>
