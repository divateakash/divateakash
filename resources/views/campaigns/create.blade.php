@extends('app')
@section('section_name', 'New Campaign')
@section('buttons_right')
<span class="ml-3 shadow-sm rounded-md">
  <a href="{{ route('campaigns.index') }}" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-500 focus:outline-none focus:shadow-outline-gray focus:border-gray-700 active:bg-gray-700 transition duration-150 ease-in-out">
    {{ __('Go Back') }}
  </a>
</span>
@endsection
@section('content')
@livewire('create-campaign')
@endsection


