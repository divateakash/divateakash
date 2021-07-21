@extends('app')
@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-md py-4 px-4">
  <form method="POST" action="{{ route('settings.store') }}" enctype="multipart/form-data">
    @csrf
  <div>
    @include('errors')
    <div>
      <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-3">
        <div class="sm:col-span-3">
          <label for="title" class="block text-sm font-medium leading-5 text-gray-700">
            Team Name
          </label>
          <div class="sm:w-1/2 mt-1 flex rounded-md shadow-sm">
            <input name="name" class="flex-1 form-input block w-full rounded-none rounded-r-md transition duration-150 ease-in-out sm:text-sm sm:leading-5" value="{{ team()->name }}" />
          </div>
        </div>
        <div class="sm:col-span-3">
          <label for="title" class="block text-sm font-medium leading-5 text-gray-700">
            Storage Service
          </label>
          <div class="sm:w-1/2 mt-1 flex rounded-md shadow-sm">
            <select name="s3_type" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
              <option value="amazon" @if(team()->s3_type == 'amazon') selected @endif>Amazon S3</option>
              <option value="DO" @if(team()->s3_type == 'DO') selected @endif>Digital Ocean</option>
            </select>
          </div>
        </div>
        <div class="sm:col-span-3">
          <label for="title" class="block text-sm font-medium leading-5 text-gray-700">
            AWS Key
          </label>
          <div class="sm:w-1/2 mt-1 flex rounded-md shadow-sm">
            <input name="aws_key" class="flex-1 form-input block w-full rounded-none rounded-r-md transition duration-150 ease-in-out sm:text-sm sm:leading-5" value="{{ team()->aws_key }}" />
          </div>
        </div>
        <div class="sm:col-span-3">
          <label for="title" class="block text-sm font-medium leading-5 text-gray-700">
            AWS Secret
          </label>
          <div class="sm:w-1/2 mt-1 flex rounded-md shadow-sm">
            <input name="aws_secret" class="flex-1 form-input block w-full rounded-none rounded-r-md transition duration-150 ease-in-out sm:text-sm sm:leading-5" value="{{ team()->aws_secret }}" />
          </div>
        </div>
        <div class="sm:col-span-3">
          <label for="title" class="block text-sm font-medium leading-5 text-gray-700">
            S3 Bucket
          </label>
          <div class="sm:w-1/2 mt-1 flex rounded-md shadow-sm">
            <input name="aws_bucket" class="flex-1 form-input block w-full rounded-none rounded-r-md transition duration-150 ease-in-out sm:text-sm sm:leading-5" value="{{ team()->aws_bucket }}" />
          </div>
        </div>

        <div class="sm:col-span-3">
          <label for="title" class="block text-sm font-medium leading-5 text-gray-700">
            Voluum API User
          </label>
          <div class="sm:w-1/2 mt-1 flex rounded-md shadow-sm">
            <input name="voluum_api_user" class="flex-1 form-input block w-full rounded-none rounded-r-md transition duration-150 ease-in-out sm:text-sm sm:leading-5" value="{{ team()->voluum_api_user }}" />
          </div>
        </div>

        <div class="sm:col-span-3">
          <label for="title" class="block text-sm font-medium leading-5 text-gray-700">
            Voluum API Key
          </label>
          <div class="sm:w-1/2 mt-1 flex rounded-md shadow-sm">
            <input name="voluum_api_key" class="flex-1 form-input block w-full rounded-none rounded-r-md transition duration-150 ease-in-out sm:text-sm sm:leading-5" value="{{ team()->voluum_api_key }}" />
          </div>
        </div>
      </div>
    </div>

  </div>
  <div class="mt-8 border-t border-gray-200 pt-5">
    <div class="flex justify-between">
      {{-- <a href="" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:border-blue-700 focus:shadow-outline-indigo active:bg-blue-700 transition duration-150 ease-in-out">Reset Password</a> --}}
      <span class="ml-3 inline-flex rounded-md shadow-sm">
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-indigo active:bg-blue-700 transition duration-150 ease-in-out">
          Save Changes
        </button>
      </span>
    </div>
  </div>
</form>
</div>
@endsection
