<div class="mt-4 flex flex-col">
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-6">
      <div class="align-middle inline-block min-w-full  overflow-hidden sm:rounded-lg">
        <table class="min-w-full">
          <thead>
            <tr>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Domain
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Sent
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wide">
                Added At
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wide"></th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach($domains as $domain)
            <tr>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 block">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $domain->domain }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">
                  {{ $domain->status }}
                  @if ($domain->error)
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                      {{ $domain->error }}
                    </span>
                  @endif
                </div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    {{ $domain->outbounds_count }}
                  </span>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $domain->created_at }}</div>
              </td>
              <td class="px-6 py-4 text-right whitespace-no-wrap border-b border-gray-200">
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
