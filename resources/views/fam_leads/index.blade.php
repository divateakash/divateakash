@extends('app')
@section('section_name', 'FAM Leads')
@section('content')
<div class="bg-white py-5 shadow overflow-hidden sm:rounded-md">
  <div class="px-4 flex items-center justify-between flex-wrap sm:flex-no-wrap">
    <div class="">
    </div>
  </div>
  <div class="mt-4 flex flex-col">
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
      <div class="align-middle inline-block min-w-full  overflow-hidden sm:rounded-lg">
        <div class="px-3">
          <form method="POST" action="{{ route('fam_leads.store') }}" enctype="multipart/form-data" class="mt-2">
            @csrf
            <input class="w-64" type="file" name="file" accept=".csv">
            <span class="shadow-sm rounded-md">
              <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
              Upload File
              </button>
            </span>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="bg-white mt-8 py-5 shadow overflow-hidden sm:rounded-md">
  <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
    <h3 class="text-lg leading-6 font-medium text-gray-900">
    Uploaded Files
    </h3>
  </div>
  <div class="mt-4 flex flex-col">
<div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-3">
                <ul class="border border-gray-200 rounded-md">
                  @foreach ($files as $file)
                  <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm leading-5">
                    <div class="w-0 flex-1 flex items-center">
                      <svg class="flex-shrink-0 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                      </svg>
                      <span class="ml-2 flex-1 w-0 truncate">
                        {{ $file->name }} — Uploaded at {{ $file->created_at }}
                      </span>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                      <a href="{{ route('fam_leads.download', $file) }}" target="_blank" class="font-medium text-blue-600 hover:text-indigo-500 transition duration-150 ease-in-out">
                        Download
                      </a>
                    </div>
                  </li>
                  @endforeach
                </ul>
              </dd>
            </div>
  </div>
  {{-- @include('appointments.partials.appointments_list') --}}
</div>
@endsection
