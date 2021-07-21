@extends('app')
@section('section_name', 'New Domain Group')
@section('buttons_right')
<span class="ml-3 shadow-sm rounded-md">
  <a href="{{ route('domain-groups.index') }}" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-500 focus:outline-none focus:shadow-outline-gray focus:border-gray-700 active:bg-gray-700 transition duration-150 ease-in-out">
    {{ __('Go Back') }}
  </a>
</span>
@endsection
@section('content')
<div class="bg-white px-4 py-5 shadow overflow-hidden sm:rounded-md">

<form method="POST" action="{{ $domainGroup->id ? route('domain-groups.update', $domainGroup) : route('domain-groups.store') }}">
  @csrf
  @if ($domainGroup->id)
    @method('PUT')
  @endif
  <div>
    <div>
      <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">
        Domain Group Information
        </h3>
      </div>
      <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
        <div class="sm:col-span-6">
          <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
            Name
          </label>
          <div class="mt-1 rounded-md shadow-sm">
            <input name="name" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" value="{{ $domainGroup->name }}" />
          </div>
        </div>
      </div>

      <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
        <div class="sm:col-span-6">
          <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
            Domain Provider
          </label>
          <div class="mt-1 rounded-md shadow-sm">
              <select name="domain_provider_id" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                @foreach (team()->domainProviders as $provider)
                  <option value="{{ $provider->id }}" @if ($provider->id == $domainGroup->domain_provider_id) selected @endif>{{ $provider->name }}</option>
                @endforeach
              </select>
          </div>
        </div>
      </div>


    </div>
  </div>
  <div class="mt-8 border-t border-gray-200 pt-5">
    <div class="flex justify-end">
      <span class="ml-3 inline-flex rounded-md shadow-sm">
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out">
        Add Domain Group
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
