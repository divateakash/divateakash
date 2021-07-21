@extends('app')
@section('section_name', 'New Message Group')
@section('buttons_right')
<span class="ml-3 shadow-sm rounded-md">
  <a href="{{ route('domain-providers.index') }}" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-500 focus:outline-none focus:shadow-outline-gray focus:border-gray-700 active:bg-gray-700 transition duration-150 ease-in-out">
    {{ __('Go Back') }}
  </a>
</span>
@endsection
@section('content')
<div class="bg-white px-4 py-5 shadow overflow-hidden sm:rounded-md">

<form method="POST" action="{{ $domainProvider->id ? route('domain-providers.update', $domainProvider) : route('domain-providers.store') }}">
  @csrf
  @if ($domainProvider->id)
    @method('PUT')
  @endif
  <div>
    <div>
      <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">
        Domain Provider Information
        </h3>
      </div>
      <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
        <div class="sm:col-span-6">
          <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
            Name
          </label>
          <div class="mt-1 rounded-md shadow-sm">
            <input name="name" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" value="{{ $domainProvider->name }}" />
          </div>
        </div>
      </div>

      <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
        <div class="sm:col-span-6">
          <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
            API User
          </label>
          <div class="mt-1 rounded-md shadow-sm">
            <input name="user" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" value="{{ $domainProvider->user }}" />
          </div>
        </div>
      </div>

      <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
        <div class="sm:col-span-6">
          <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
            API Password
          </label>
          <div class="mt-1 rounded-md shadow-sm">
            <input name="password" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" value="{{ $domainProvider->password }}" />
          </div>
        </div>
      </div>


    </div>
  </div>
  <div class="mt-8 border-t border-gray-200 pt-5">
    <div class="flex justify-end">
      <span class="ml-3 inline-flex rounded-md shadow-sm">
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out">
        Add Message Group
        </button>
      </span>
    </div>
  </div>
</form>


</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
$('.members-select').select2();
});
</script>
@endsection
