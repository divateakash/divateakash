@extends('app')
@section('section_name', "Viewing Domain Group $domainGroup->name")
@section('content')

<div class="mt-8 bg-white py-5 shadow overflow-hidden sm:rounded-md">
  <div class="px-3 flex items-center justify-between flex-wrap sm:flex-no-wrap">
    <div class="flex inline-flex items-center">
      <div class="flex inline-flex items-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
        Domains
        </h3>
        <span class="sm:ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
          {{ $domainGroup->domains_count }} domains
        </span>
      </div>
    </div>
    <span class="ml-3 shadow-sm rounded-md">
      <form method="POST" action="{{ route('domain_groups.domains.store', $domainGroup) }}" enctype="multipart/form-data">
        @csrf
        <input class="w-64" type="file" name="file" accept=".csv">
        <span class="shadow-sm rounded-md">
          <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
          Upload Domains
          </button>
        </span>
      </form>
    </span>
  </div>
  @include('domain_groups.partials.domains_table')
</div>

@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    $('.select2').select2();
});
</script>
@endsection
