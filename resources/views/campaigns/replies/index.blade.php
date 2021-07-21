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
    <div class="">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
      Replies
      </h3>
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
                Message
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider flex justify-end">
                Sent At
              </th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach($replies as $reply)
            <tr>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 block">
                <div class="text-sm leading-5 font-medium text-gray-900">
                  @if ($reply->isGood())
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                      Good
                     </span>
                  @elseif ($reply->isBad())
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                       Bad
                    </span>
                  @else
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                      Neutral
                    </span>
                  @endif
                </div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $reply->from }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $reply->to }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $reply->content }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 flex justify-end">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $reply->created_at }}</div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div class="mt-4 p-6">
          {{ $replies->withQueryString()->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
