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
  <div class="px-3 flex items-center justify-between flex-wrap sm:flex-no-wrap">
      <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">
        Outbounds
        </h3>
      </div>
      <div>
        <div>
          <a href="{{ route('campaigns.outbounds.index', [$campaign, 'filter' => 'pending']) }}" class="font-semibold text-sm text-indigo-600 hover:text-indigo-500">Pending</a>
          <a href="{{ route('campaigns.outbounds.index', [$campaign, 'filter' => 'failure']) }}" class="font-semibold text-sm text-red-600 hover:text-red-500">Failures only</a>
          <a href="{{ route('campaigns.outbounds.index', [$campaign, 'filter' => 'success']) }}" class="ml-2 font-semibold text-sm text-green-600 hover:text-green-500">Successes only</a>
          <a href="{{ route('campaigns.outbounds.index', [$campaign, 'filter' => 'delivered']) }}" class="ml-2 font-semibold text-sm text-green-600 hover:text-green-500">Delivered only</a>
          <a href="{{ route('campaigns.outbounds.index', [$campaign, 'filter' => 'replies']) }}" class="ml-2 font-semibold text-sm text-indigo-600 hover:text-indigo-500">Replies only</a>
          <a href="{{ route('campaigns.outbounds.index', $campaign) }}" class="ml-2 font-semibold text-sm text-blue-600 hover:text-blue-500">Sent</a>
        </div>
        <div class="flex justify-end mt-4">
          <x-dropdown variable="dlrStatusOpen" name="{{ request('dlrStatus') ?? 'Delivery Status' }}">
              <a href="{{ route('campaigns.outbounds.index', ['campaign' => $campaign, 'filter' => request('filter')]) }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">All</a>
              <a href="{{ route('campaigns.outbounds.index', ['campaign' => $campaign, 'filter' => request('filter'), 'dlrStatus' => 'Delivered']) }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">Delivered</a>
              <a href="{{ route('campaigns.outbounds.index', ['campaign' => $campaign, 'filter' => request('filter'), 'dlrStatus' => 'Rejected']) }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">Rejected</a>
          </x-dropdown>
        </div>
      </div>
  </div>

  @livewire('outbounds-carrier-breakdown', ['campaign' => $campaign])

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
                Carrier
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Content
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Account
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Sent At
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider flex justify-end">
                Response
              </th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach($outbounds as $outbound)
            <tr>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 block">
                <div class="text-sm leading-5 font-medium text-gray-900">
                  @if ($outbound->success)
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                      Sent
                     </span>
                  @elseif ($outbound->processed)
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        Failed
                    </span>
                  @endif

                  @if ($outbound->deliveryReport)
                    @if ($outbound->deliveryReport->delivered())
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Delivered
                     </span>
                    @else
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        {{ $outbound->deliveryReport->error ?? 'Rejected' }}
                      </span>
                    @endif
                  @endif
                </div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $outbound->from }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $outbound->to }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $outbound->lead->carrier }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <a href="{{ route('message-groups.show', $outbound->message_group_id) }}" class="text-sm leading-5 font-semibold text-blue-600 hover:text-blue-500">{{ $outbound->content }}</a>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                @if ($outbound->account)
                  <a href="{{ route('accounts.show', $outbound->account) }}" class="text-sm leading-5 font-semibold text-blue-600 hover:text-blue-500">{{ $outbound->account->name }}</a>
                @else
                  <span class="text-sm leading-5 font-semibold text-red-600 hover:text-red-500">Not Available</span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $outbound->sent_at ?? "Scheduled to be sent in {$outbound->send_at->diffForHumans()}" }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 flex justify-end">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $outbound->response }}</div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

      <div class="mt-4 p-6">
        {{ $outbounds->withQueryString()->links() }}
      </div>
      </div>
    </div>
  </div>
</div>
@endsection
