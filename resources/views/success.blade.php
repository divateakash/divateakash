@if(session('success'))
<div class="rounded-md bg-green-50 p-4 my-4">
  <h3 class="text-sm leading-5 font-medium text-green-800">
    {{ session('success') }}
  </h3>
</div>
@endif
