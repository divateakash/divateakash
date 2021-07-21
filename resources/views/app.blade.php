<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" />
    <title>{{ app_name() }}</title>

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css" />
    @livewireStyles
  </head>
  <body class="bg-gray-100 text-gray-900 antialiased font-sans">

<div class="min-h-screen bg-gray-100">
  @include('partials.nav')

  <div class="py-10">
    <header>
      <div class="@hasSection('full_width') max-w-full @else max-w-7xl @endif mx-auto px-4 pb-8 sm:px-6 lg:px-8">
        @yield('breadcumbs')

        @hasSection('section_name')
          <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
              <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
                @yield('section_name')
              </h2>
            </div>
          @hasSection('buttons_right')
            <div class="mt-4 flex md:mt-0 md:ml-4">
              @yield('buttons_right')
            </div>
          @endif
        </div>
        @endif
      </div>
    </header>
    <main>
      <div class="@hasSection('full_width') max-w-full @else max-w-7xl @endif mx-auto sm:px-6 lg:px-8">
          @yield('content')
      </div>
    </main>
  </div>
</div>

<script src="{{ mix('js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@2.0.1/dist/alpine.js" defer></script>
<script src="https://cdn.ckeditor.com/ckeditor5/18.0.0/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>
  <script>
  $(function() {
    $( "#datepicker" ).datetimepicker({ dateFormat: 'mm-dd-yy' });
  });
  </script>
@yield('scripts')
@livewireScripts
  </body>
</html>
