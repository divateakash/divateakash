<div class="mt-8 bg-white py-5 shadow overflow-hidden sm:rounded-md">
  <div class="px-3 flex items-center justify-between flex-wrap sm:flex-no-wrap">
    <div class="flex inline-flex items-center">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
      Attach Account
      </h3>
    </div>
  </div>


  <div class="mt-4 flex flex-col">
    <form method="POST" action="{{ route('accounts.sub_accounts.store', $account) }}" class="py-2 overflow-x-auto  sm:px-6 lg:px-6">
      @csrf
        <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
              <label for="country" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                Account
              </label>
              <div class="mt-1 sm:mt-0 sm:col-span-2">
                <div class="max-w-lg rounded-md shadow-sm sm:max-w-xs">
                  <select class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 select2" name="accounts[]" multiple="multiple">
                    @foreach ($availableAccounts as $availableAccount)
                      <option value="{{ $availableAccount->id }}">{{ $availableAccount->name }} - {{ $availableAccount->numbers_count }} numbers — {{ $availableAccount->provider->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="mt-8 border-t border-gray-200 pt-5">
        <div class="flex justify-end">
          <span class="ml-3 inline-flex rounded-md shadow-sm">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
              Attach
            </button>
          </span>
        </div>
      </div>

    </form>
  </div>

</div>

<div class="mt-8 bg-white py-5 shadow overflow-hidden sm:rounded-md">
  <div class="px-3 flex items-center justify-between flex-wrap sm:flex-no-wrap">
    <div class="flex inline-flex items-center">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
      Attached Accounts
      </h3>
      <span class="sm:ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
        Account Group
      </span>
    </div>
  </div>

  <div class="mt-4 flex flex-col">
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-6">
      <div class="align-middle inline-block min-w-full  overflow-hidden sm:rounded-lg">
        <table class="min-w-full">
          <thead>
            <tr>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                #
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Name
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Provider
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wide">
                Numbers
                </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wide">
                Opt-Out (24h)
              </th>

              <th></th>
            </tr>
          </thead>
          <tbody class="bg-white">

            @foreach($attachedAccounts as $subAccount)
            <tr>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 block">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $subAccount->id }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium"><a href="{{ route('accounts.show', $subAccount) }}" class="font-semibold text-blue-600 hover:text-blue-500">{{ $subAccount->name }}</a></div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    {{ $subAccount->provider->name }}
                  </span>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    {{ $subAccount->numbers_count }}
                  </span>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                    {{ $subAccount->averageOptOut() }}%
                  </span>
              </td>

              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-right">
                <form method="POST" action="{{ route('accounts.sub_accounts.delete', [$account, $subAccount]) }}">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-red-600 hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-700 transition duration-150 ease-in-out" >Detach Account</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<div class="mt-8 bg-white py-5 shadow overflow-hidden sm:rounded-md">
  <div class="px-3 flex items-center justify-between flex-wrap sm:flex-no-wrap">
    <div class="flex inline-flex items-center">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
      Activity Log
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
                Activity
              </th>
              <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Happened At
              </th>
            </tr>
          </thead>
          <tbody class="bg-white">

            @foreach($logs as $activity)
            <tr>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 block">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $activity->content }}</div>
              </td>
              <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <div class="text-sm leading-5 font-medium text-gray-900">{{ $activity->created_at }}</div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
