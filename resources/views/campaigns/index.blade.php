@extends('app')

@section('section_name', 'Campaigns')
@section('full_width', true)

@section('buttons_right')
<span class="ml-3 shadow-sm rounded-md">
  <a href="{{ route('campaigns.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
    New Campaign
  </a>
</span>
@endsection

@section('content')
<div class="bg-white py-5 shadow overflow-hidden sm:rounded-md">
    <div class="px-4 flex items-center justify-between flex-wrap sm:flex-no-wrap">
      <div class="">

      </div>
    <div class="ml-4 mt-2 flex-shrink-0">
      <x-dropdown variable="teamSelectorOpen" name="Status">
          <a href="{{ route('campaigns.index') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">New</a>
          <a href="{{ route('campaigns.index', ['old' => true]) }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">Old</a>
      </x-dropdown>
    </div>
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
              Started At
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
            <th class="px-3 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
              Conversions
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
