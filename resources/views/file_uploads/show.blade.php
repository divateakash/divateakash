@extends('app')
@section('section_name', __('Viewing File Upload'))
@section('buttons_right')
<span class="ml-3 shadow-sm rounded-md">
  <a href="{{ route('lists.show', $fileUpload->catalog) }}" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-500 focus:outline-none focus:shadow-outline-gray focus:border-gray-700 active:bg-gray-700 transition duration-150 ease-in-out">
    {{ __('Go Back') }}
  </a>
</span>
@endsection
@section('content')
<div class="bg-white px-4 py-5 shadow overflow-hidden sm:rounded-md">
  <form method="POST" action="{{ route('file_uploads.process', $fileUpload) }}">
    @csrf
    <div>
      <div>
        <div>
          <h3 class="text-lg leading-6 font-medium text-gray-900">
          Map Fields
          </h3>
          <p class="text-sm mt-2">
            Although only the phone field is required, we recommend filling as many as possible. Please select the column that indicates them on your file.
          </p>
        </div>
        @if ($fileUpload->processed_at)
        <div class="rounded-md bg-green-50 p-4 my-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm leading-5 font-medium text-green-800">
              Hey!
              </h3>
              <div class="mt-2 text-sm leading-5 text-green-700">
                <span>It seems this file upload started it's processing at {{ $fileUpload->processed_at }}. <br> Please give it some time to work it's content — if you think it hasn't ever finished, please try uploading it again.</span>
              </div>
            </div>
          </div>
        </div>
        @else
        <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
          <div class="sm:col-span-6">
            <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
              File Name
            </label>
            <div class="mt-1 rounded-md shadow-sm">
              <span>{{ $fileUpload->name }}</span>
            </div>
          </div>
          <div class="sm:col-span-6">
            <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
              Phone
            </label>
            <div class="mt-1 rounded-md shadow-sm">
              <select name="phone" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                <option selected disabled>None</option>
                @foreach ($fileUpload->headers as $index => $header)
                <option value="{{ $index }}">{{ $header }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="sm:col-span-6">
            <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
              First Name
            </label>
            <div class="mt-1 rounded-md shadow-sm">
              <select name="first_name" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                <option selected disabled>None</option>
                @foreach ($fileUpload->headers as $index => $header)
                <option value="{{ $index }}">{{ $header }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="sm:col-span-6">
            <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
              Last Name
            </label>
            <div class="mt-1 rounded-md shadow-sm">
              <select name="last_name" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                <option selected disabled>None</option>
                @foreach ($fileUpload->headers as $index => $header)
                <option value="{{ $index }}">{{ $header }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="sm:col-span-6">
            <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
              Email
            </label>
            <div class="mt-1 rounded-md shadow-sm">
              <select name="email" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                <option selected disabled>None</option>
                @foreach ($fileUpload->headers as $index => $header)
                <option value="{{ $index }}">{{ $header }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="sm:col-span-6">
            <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
              State
            </label>
            <div class="mt-1 rounded-md shadow-sm">
              <select name="state" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                <option selected disabled>None</option>
                @foreach ($fileUpload->headers as $index => $header)
                <option value="{{ $index }}">{{ $header }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="sm:col-span-6">
            <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
              City
            </label>
            <div class="mt-1 rounded-md shadow-sm">
              <select name="city" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                <option selected disabled>None</option>
                @foreach ($fileUpload->headers as $index => $header)
                <option value="{{ $index }}">{{ $header }}</option>
                @endforeach
              </select>
            </div>
          </div>

        </div>
        @endif
        @unless ($fileUpload->processed_at)
          <div class="mt-8 border-t border-gray-200 pt-5">
            <div class="flex justify-end">
              <span class="ml-3 inline-flex rounded-md shadow-sm">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out">
                Map and process
                </button>
              </span>
            </div>
          </div>
        @endunless
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
