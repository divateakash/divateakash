<div class="mt-8 bg-white py-5 shadow overflow-hidden sm:rounded-md">
    <div class="px-3 flex items-center justify-between flex-wrap sm:flex-no-wrap">
    <div class="flex inline-flex items-center">
      <div class="flex inline-flex items-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
        {{ Str::studly($this->type) }} Reply Words
        </h3>
      </div>
    </div>
  </div>
  <form method="GET" class="ml-3 mt-4">
  <label class="block text-sm font-medium leading-5 text-gray-700">Add Word</label>
    <div class="md:w-1/3 flex rounded-md shadow-sm">
      <div class="relative flex-grow focus-within:z-10">
        <input wire:model="newWord" class="form-input block w-full rounded-none rounded-l-md transition ease-in-out duration-150 sm:text-sm sm:leading-5">
      </div>
      <button wire:click.prevent="addWord" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-r-md text-blue-100 bg-blue-700 hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-blue-100 active:text-blue-700 transition ease-in-out duration-150">
        <span class="ml-2">Add Word</span>
      </button>
    </div>
  <label class="mt-4 block text-sm font-medium leading-5 text-gray-700">Filter</label>
  <div class="mt-1 md:w-1/3 flex rounded-md shadow-sm">
    <div class="relative flex-grow focus-within:z-10">
      <input wire:model="filter" wire:keyup.debounce="filterData" class="form-input block w-full rounded-none rounded-l-md transition ease-in-out duration-150 sm:text-sm sm:leading-5">
    </div>
{{--     <button type="submit" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
      Heroicon name: sort-ascending
      <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
        <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
      </svg>
      <span class="ml-2">Filter</span>
    </button> --}}
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
                  Added At
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wide"></th>
              </tr>
            </thead>
            <tbody class="bg-white">
              @foreach($words as $word)
              <tr>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 block">
                  <div class="text-sm leading-5 font-medium text-gray-900">
                    <span class="px-2 inline-flex  leading-5 font-semibold rounded-full {{ $this->colorClasses }}">
                      {{ $word->word }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                  <div class="text-sm leading-5 font-medium text-gray-900">{{ $word->created_at }}</div>
                </td>
                <td class="px-6 py-4 text-right whitespace-no-wrap border-b border-gray-200">
                  <button wire:click="deleteWord('{{ $word->id }}')" class="text-red-600 hover:text-red-900">Delete</button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
