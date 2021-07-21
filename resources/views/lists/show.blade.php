@extends('app')
@section('section_name', "Viewing List $list->name")
@section('content')
@include('errors')

<div class="rounded-md bg-green-50 p-4 mb-8">
  <div class="flex">
    <div class="flex-shrink-0">
      <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
      </svg>
    </div>
    <div class="ml-3">
      <h3 class="text-sm leading-5 font-medium text-green-800">
        Inbound API
      </h3>
      <div class="mt-2 text-sm leading-5 text-green-700">
        <p>
          To add leads to this list through an external service, send a POST to the following endpoint: <strong>{{ route('leads.store', $list) }}</strong>
        </p>
        <p>
          Accepted fields: <strong>phone, email, first_name, last_name</strong>
      </div>
    </div>
  </div>
</div>

<div class="bg-white py-5 shadow overflow-hidden sm:rounded-md">
  <div class="px-3 flex items-center justify-between flex-wrap sm:flex-no-wrap">
    <div class="">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
      Uploads
      </h3>
    </div>
    <span class="ml-3 shadow-sm rounded-md">
      <form method="POST" action="{{ route('file-uploads.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="catalog_id" value="{{ $list->id }}">
        <input class="w-64" type="file" name="file" accept=".csv">
        <span class="shadow-sm rounded-md">
          <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
          Upload File
          </button>
        </span>
      </form>
    </span>
  </div>
  <div class="mt-4 flex flex-col">
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-6">
      <div class="align-middle inline-block min-w-full  overflow-hidden sm:rounded-lg">
        <table class="min-w-full">
          <thead>
            <tr>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Name
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Duplicates
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Rejected
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Landlines
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Processed At
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach($list->fileUploads as $fileUpload)
            <tr>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 block">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $fileUpload->name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <span class="px-6 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                  {{ $fileUpload->duplicates ?? 0 }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <span class="px-6 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                  {{ $fileUpload->rejected ?? 0 }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <span class="px-6 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                  {{ $fileUpload->landlines ?? 0 }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $fileUpload->processed_at }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                <a href="{{ route('file-uploads.show', $fileUpload) }}" class="text-blue-600 hover:text-blue-900">Mapping</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@include('lists.partials.carrier_breakdown')

<div class="mt-8 bg-white py-5 shadow overflow-hidden sm:rounded-md">
  <div class="px-3 flex items-center justify-between flex-wrap sm:flex-no-wrap">
    <div class="sm:flex sm:inline-flex sm:items-center">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
      Leads
      </h3>
      <div class="sm:ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $list->leads_count }} leads</div>
      <div class="sm:ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ $list->suppressed_leads_count }} suppressed</div>
    </div>
  </div>
  <div class="mt-4 flex flex-col">
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-6">
      <div class="align-middle inline-block min-w-full  overflow-hidden sm:rounded-lg">
        <table class="min-w-full">
          <thead>
            <tr>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Phone
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                First Name
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Last Name
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Email
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                State
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                City
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider flex justify-end">
                Carrier
              </th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach($leads as $lead)
            <tr>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 block">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $lead->phone }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $lead->first_name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $lead->last_name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $lead->email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $lead->region }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $lead->city }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 flex justify-end">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $lead->carrier }}</div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
