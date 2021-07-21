@extends('app')
@section('section_name', "Viewing Provider $provider->name")

@section('buttons_right')
<span class="ml-3 shadow-sm rounded-md">
  <a href="{{ route('providers.index') }}" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-500 focus:outline-none focus:shadow-outline-gray focus:border-gray-700 active:bg-gray-700 transition duration-150 ease-in-out">
    {{ __('Go Back') }}
  </a>
</span>
@endsection

@section('content')
<div class="bg-white py-5 shadow overflow-hidden sm:rounded-md">
  <div class="px-3 flex items-center justify-between flex-wrap sm:flex-no-wrap">
    <div class="">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
      Accounts
      </h3>
    </div>
    <span class="ml-3 shadow-sm rounded-md">
        <span class="shadow-sm rounded-md">
          <a href="#" class="inline-flex items-center px-6 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
          Numbers
          </a>
        </span>
    </span>
  </div>
  <div class="mt-4 flex flex-col">
    <div class="p-4">
      <div class="rounded-md bg-green-50 p-4 mb-8">
        <div class="flex">
          <div class="ml-3">
            <h3 class="text-sm leading-5 font-medium text-green-800">
              Configuration
            </h3>
            <div class="mt-2 text-sm leading-5 text-green-700">
              <p>
                If your provider allows you to configure those, please set these values:
              </p>
              <p>
                Inbound message URL: <strong>{{ route('providers.webhook', $provider->provider) }}</strong>
              </p>
              <p>
                Delivery Report URL: <strong>{{ route('providers.delivery_report', $provider->provider) }}</strong>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-6">
      <div class="align-middle inline-block min-w-full  overflow-hidden sm:rounded-lg">
        <table class="min-w-full">
          <thead>
            <tr>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Name
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Type
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Send Rate
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Numbers
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach($accounts as $account)
            <tr>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 block">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $account->name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <span class="px-6 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                  {{ $account->type }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <span class="px-6 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                  {{ $account->send_rate }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <span class="px-6 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                  {{ $account->numbers_count }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                <a href="{{ route('accounts.show', $account) }}" class="text-blue-600 hover:text-blue-900">More</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- <div class="mt-8 bg-white py-5 shadow overflow-hidden sm:rounded-md">
  <div class="px-3 flex items-center justify-between flex-wrap sm:flex-no-wrap">
    <div class="">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
      Reserve Numbers
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
                Number
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach($provider->reserveNumbers as $number)
              <tr>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 block">
                  <div class="text-sm leading-5 font-medium text-gray-900">{{ $number->number }}</div>
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                  <div class="text-sm leading-5 font-medium text-gray-900">{{ $number->status }}</div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div> --}}
@endsection
