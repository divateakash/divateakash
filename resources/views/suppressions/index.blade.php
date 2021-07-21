@extends('app')
@section('section_name', 'Suppressions')
@section('buttons_right')

@endsection
@section('content')
<div class="mt-8 bg-white py-5 shadow overflow-hidden sm:rounded-md">
  <div class="px-3 flex items-center justify-between flex-wrap sm:flex-no-wrap">
    <div class="flex inline-flex items-center">
      <div class="flex inline-flex items-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
        Suppressions
        </h3>
      </div>
    </div>
    <span class="ml-3 shadow-sm rounded-md">
      <form method="POST" action="{{ route('suppressions.file.store') }}" enctype="multipart/form-data">
        @csrf
        <input class="w-64" type="file" name="file" accept=".csv">
        <span class="shadow-sm rounded-md">
          <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
          Upload Suppression List
          </button>
        </span>
      </form>
    </span>
  </div>

  <form method="GET" class="ml-4">
  <label for="email" class="block text-sm font-medium leading-5 text-gray-700">Filter by phone</label>
  <div class="mt-1 md:w-1/3 flex rounded-md shadow-sm">
    <div class="relative flex-grow focus-within:z-10">
      <input value="{{ request('phone') }}" name="phone" class="form-input block w-full rounded-none rounded-l-md transition ease-in-out duration-150 sm:text-sm sm:leading-5">
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
                  Suppressed At
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wide"></th>
              </tr>
            </thead>
            <tbody class="bg-white">
              @foreach($suppressions as $suppression)
              <tr>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 block">
                  <div class="text-sm leading-5 font-medium text-gray-900">{{ $suppression->phone }}</div>
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                  <div class="text-sm leading-5 font-medium text-gray-900">{{ $suppression->created_at }}</div>
                </td>
                <td class="px-6 py-4 text-right whitespace-no-wrap border-b border-gray-200">
                  <form method="POST" action="{{ route('suppressions.destroy', $suppression) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="mt-2 px-4">
        {{ $suppressions->links() }}
      </div>
    </div>

</div>
@endsection
