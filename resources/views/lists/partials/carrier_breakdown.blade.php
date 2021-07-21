<div class="bg-white py-5 shadow overflow-hidden sm:rounded-md mt-8">
  <div class="px-3 flex items-center justify-between flex-wrap sm:flex-no-wrap">
    <div class="">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
      Carrier Breakdown
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
                Name
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Count
              </th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach($carrierBreakdown as $carrier)
              <tr>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                  <div class="text-sm leading-5 font-medium text-gray-900">{{ $carrier['carrier']->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                  {{ $carrier['count'] }}
                </td>
              </tr>
            @endforeach
            <tr>
              <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-bold text-gray-900">Total</td>
              <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-bold text-gray-900">{{ $carrierBreakdown->sum('count') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
