<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="{{ asset('/plugins/bootstrap5/css/bootstrap.min.css') }}" rel="stylesheet">
    <title>Installation Page</title>
  </head>
  <body>
    {{ $slot }}
    <!-- Bootstrap Bundle with Popper -->
    <script src="{{ asset('/plugins/bootstrap5/js/bootstrap.min.js') }}"></script>
    @yield('scripts')
  </body>
</html>