<div wire:init="load" class="flex flex-col mt-8">
  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <div class="p-4 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
        Carrier Breakdown
        </h3>
        <table class="min-w-full divide-y divide-gray-200">
          <thead>
            <tr>
              <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Carrier
              </th>
              <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Messages
              </th>
            </tr>
          </thead>
          <tbody>
            @if ($readyToLoad)
            <!-- Odd row -->
              @foreach ($carriers as $carrier)
                <tr>
                  <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                    {{ $carrier['carrier']->name }}
                  </td>
                  <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                    {{ $carrier['count'] }}
                  </td>
                </tr>
              @endforeach
              <tr>
                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-bold text-gray-900">Total</td>
                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-bold text-gray-900">{{ $this->totalCount }}</td>
              </tr>
            @else
              Loading...
            @endif
            <!-- More rows... -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
