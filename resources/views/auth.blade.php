<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" />
    <title>{{ config('app.name') }}</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  </head>
  <body class="bg-gray-100 text-gray-900 antialiased font-sans">

    <div>
      @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@2.0.1/dist/alpine.js" defer></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/18.0.0/classic/ckeditor.js"></script>
    @yield('scripts')
  </body>
</html>
