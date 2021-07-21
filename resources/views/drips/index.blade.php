@extends('app')

@section('section_name', 'Drips')
@section('full_width', true)

@section('buttons_right')
<span class="ml-3 shadow-sm rounded-md">
  <a href="{{ route('drips.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
    New Drip
  </a>
</span>
@endsection

@section('content')
<div class="bg-white py-5 shadow overflow-hidden sm:rounded-md">
    <div class="px-4 flex items-center justify-between flex-wrap sm:flex-no-wrap">
      <div class="">

      </div>
  </div>

<div class="mt-2 px-4">

<!--
  Tailwind UI components require Tailwind CSS v1.8 and the @tailwindcss/ui plugin.
  Read the documentation to get started: https://tailwindui.com/documentation
-->
<form method="GET">
  <label for="email" class="block text-sm font-medium leading-5 text-gray-700">Filter drips</label>
  <div class="mt-1 md:w-1/3 flex rounded-md shadow-sm">
    <div class="relative flex-grow focus-within:z-10">
      <input value="{{ request('filter') }}" name="filter" class="form-input block w-full rounded-none rounded-l-md transition ease-in-out duration-150 sm:text-sm sm:leading-5">
    </div>
    <button type="submit" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
      <!-- Heroicon name: sort-ascending -->
      <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
        <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
      </svg>
      <span class="ml-2">Filter</span>
    </button>
  </div>
</form>

</div>

<div class="mt-4 flex flex-col">
  <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
    <div class="align-middle inline-block min-w-full  overflow-hidden sm:rounded-lg">
      <table class="min-w-full">
        <thead>
          <tr>
            <th class="px-3 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
              Name
            </th>
            <th class="px-3 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
              Send message after
            </th>
            <th class="px-3 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
              Account
            </th>
            <th class="px-3 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
              List
            </th>
            <th class="px-3 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
              Message Group
            </th>
            <th class="px-3 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
              Link Type
            </th>
            <th class="px-3 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
              Sent
            </th>
            <th class="px-3 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
              Failed
            </th>
            <th class="px-3 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
              Replies
            </th>
            <th class="px-3 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
              Clicks
            </th>
            <th class="px-3 py-3 border-b border-gray-200 bg-gray-50"></th>
          </tr>
        </thead>
        <tbody class="bg-white">
          @foreach ($campaigns as $campaign)
            @livewire('campaign', ['campaign' => $campaign])
          @endforeach
        </tbody>
      </table>

      <div class="mt-4 p-6">
        {{ $campaigns->withQueryString()->links() }}
      </div>
    </div>
  </div>
</div>

    {{-- @include('appointments.partials.appointments_list') --}}
</div>
@endsection
