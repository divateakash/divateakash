@extends('app')
@section('section_name', 'New Offer')
@section('buttons_right')
<span class="ml-3 shadow-sm rounded-md">
  <a href="{{ route('offers.index') }}" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-500 focus:outline-none focus:shadow-outline-gray focus:border-gray-700 active:bg-gray-700 transition duration-150 ease-in-out">
    {{ __('Go Back') }}
  </a>
</span>
@endsection
@section('content')
<div class="bg-white px-4 py-5 shadow overflow-hidden sm:rounded-md">

<form method="POST" action="{{ route('offers.store') }}">
  @csrf
  <div>
    <div>
      <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">
        {{ __('Offer Information') }}
        </h3>
      </div>
      <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
        <div class="sm:col-span-6">
          <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
            Name
          </label>
          <div class="mt-1 rounded-md shadow-sm">
            <input name="name" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
          </div>
        </div>
      </div>
      <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
        <div class="sm:col-span-6">
          <label name="redirect_url" class="block text-sm font-medium leading-5 text-gray-700">
            Redirect URL
          </label>
          <div class="mt-1 text-xs text-gray-800 tracking-wide">Available parameters: [FIRSTNAME] [LASTNAME] [EMAIL] [LINK]</div>
          <div class="mt-1 rounded-md shadow-sm">
            <input name="redirect_url" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="mt-8 border-t border-gray-200 pt-5">
    <div class="flex justify-end">
      <span class="ml-3 inline-flex rounded-md shadow-sm">
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out">
        Add Offer
        </button>
      </span>
    </div>
  </div>
</form>


</div>
@endsection
