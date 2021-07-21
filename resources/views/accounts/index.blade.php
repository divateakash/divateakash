@extends('app')
@section('section_name', 'Accounts')
@section('buttons_right')
<span class="ml-3 shadow-sm rounded-md">
  <a href="{{ route('accounts.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
    Add Account
  </a>
</span>
@endsection
@section('content')
<div class="bg-white py-5 shadow overflow-hidden sm:rounded-md">
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
                Type
              </th>
              <th class="px-3 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Sending Cost
              </th>
              <th class="px-3 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Send Rate
              </th>
              <th class="px-3 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Provider
              </th>
              <th class="px-3 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Numbers
              </th>
              <th class="px-3 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Created At
              </th>
              <th class="px-3 py-3 border-b border-gray-200 bg-gray-50"></th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach($accounts as $account)
            <tr>
              <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200 block">
                <div class="text-sm leading-5 font-medium text-gray-900">
                  {{ $account->name }}
                  @if ($account->is_group)
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 hover:bg-blue-50 text-blue-800">{{ $account->accounts_count }} accounts</span>
                  @endif
                </div>
                @if ($account->messageGroup)
                  <a href="{{ route('message-groups.show', $account->messageGroup) }}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 hover:bg-blue-50 text-blue-800">
                    Attached to MG {{ $account->messageGroup->name }}
                  </a>
                @endif
              </td>
              <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $account->type }}</div>
              </td>
              <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                  ${{ $account->provider->cost }}
                </span>
              </td>
              <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                  {{ $account->sendPerHour() }}/hour
                </span>
              </td>
              <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200">
                <a href="{{ route('providers.show', $account->provider) }}" class="text-blue-600 hover:text-blue-900">{{ $account->provider->name }}</a>
              </td>
              <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                  @if ($account->is_group)
                    {{ $account->getNumbersCount() }}
                  @else
                    {{ $account->numbers_count }}
                  @endif
                </span>
              </td>
              <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 text-gray-900">{{ $account->created_at }}</div>
              </td>
              <td class="px-3 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                <a href="{{ route('accounts.show', $account) }}" class="text-blue-600 hover:text-blue-900">More</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{-- @include('appointments.partials.appointments_list') --}}
</div>
@endsection
