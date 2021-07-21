@extends('app')

@section('section_name', $campaign->name)

@section('buttons_right')
<span class="ml-3 shadow-sm rounded-md">
  <a href="{{ route('campaigns.index') }}" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-500 focus:outline-none focus:shadow-outline-gray focus:border-gray-700 active:bg-gray-700 transition duration-150 ease-in-out">
    {{ __('Go Back') }}
  </a>
</span>
@endsection

@section('content')
<div class="mt-8 bg-white py-5 shadow overflow-hidden sm:rounded-md">
  @include('errors')
  <div class="px-3 flex items-center justify-between flex-wrap sm:flex-no-wrap">
    <div class="">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
      Pending Outbound Replies
      </h3>
    </div>
    <div class="flex inline-flex">
      <form action="{{ route('campaigns.pending-replies.scramble', $campaign) }}" method="POST">
        @csrf
        <button class="ml-2 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-green-600 hover:bg-green-500 focus:outline-none focus:shadow-outline-gray focus:border-gray-700 active:bg-gray-700 transition duration-150 ease-in-out">Randomize Accounts</button>
      </form>
      <form action="{{ route('campaigns.pending-replies.retry', $campaign) }}" method="POST">
        @csrf
        <button class="ml-2 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline-gray focus:border-gray-700 active:bg-gray-700 transition duration-150 ease-in-out">Retry All</button>
      </form>
    </div>
  </div>
  <div class="mt-4 flex flex-col">
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-6">
      <div class="align-middle inline-block min-w-full  overflow-hidden sm:rounded-lg">
        <table class="min-w-full">
          <thead>
            <tr>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">

              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                From
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                To
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Account
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Message Group
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider flex justify-end">
                Created At
              </th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach($outbounds as $outbound)
            <tr>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 block">
                <div class="text-sm leading-5 font-medium text-gray-900">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                    Pending
                   </span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $outbound->from }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $outbound->to }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5">
                  @if ($outbound->account)
                    <a href="{{ route('accounts.show', $outbound->account) }}" class="text-blue-600 hover:text-blue-500 font-semibold">
                       {{ $outbound->account->name }}
                    </a>
                  @endif
                </div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5">
                  <a href="{{ route('message-groups.show', $outbound->getMessageGroup()) }}" class="text-blue-600 hover:text-blue-500 font-semibold">
                     {{ $outbound->getMessageGroup()->name }}
                  </a>
                </div>
                @if ($outbound->getMessageGroup()->messages->count() == 0)
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                    Not enough messages
                   </span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 flex justify-end">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $outbound->created_at }}</div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      {{ $outbounds->links() }}
    </div>
  </div>
</div>
@endsection
