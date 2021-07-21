<div wire:init="loadAll">
    <div class="flex justify-between">
    <h3 class="text-lg leading-6 font-medium text-gray-900">
      Since {{ $this->formattedTimeframe }}
    </h3>
    <div class="ml-4 mt-2 flex-shrink-0">
      <x-dropdown variable="timeframe" name="{{ $this->formattedTimeframeLabel }}">
          <a wire:click="setTimeframe('today')" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">Today</a>
          <a wire:click="setTimeframe('week')" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">Week</a>
          <a wire:click="setTimeframe('24')" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">24 Hours</a>
          <a wire:click="setTimeframe('168')" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">7 days</a>
          <a wire:click="setTimeframe('month')" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">Month</a>
      </x-dropdown>
    </div>
  </div>
  <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
    <div class="bg-white overflow-hidden shadow rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <dl>
          <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
            Sent Outbounds
          </dt>
          <dd class="mt-1 text-3xl leading-9 font-semibold text-gray-900">
            {{ $sentOutbounds }}
          </dd>
        </dl>
      </div>
    </div>
    <div class="bg-white overflow-hidden shadow rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <dl>
          <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
            Sent Replies
          </dt>
          <dd class="mt-1 text-3xl leading-9 font-semibold text-gray-900">
            {{ $sentReplies }}
          </dd>
        </dl>
      </div>
    </div>
    <div class="bg-white overflow-hidden shadow rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <dl>
          <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
            Cost
          </dt>
          <dd class="mt-1 text-3xl leading-9 font-semibold text-gray-900">
            ${{ $cost }}
          </dd>
        </dl>
      </div>
    </div>
  </div>

  <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
    <div class="bg-white overflow-hidden shadow rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <dl>
          <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
            Good Replies
          </dt>
          <dd class="mt-1 text-3xl leading-9 font-semibold text-gray-900">
            {{ $goodReplies }}
          </dd>
        </dl>
      </div>
    </div>
    <div class="bg-white overflow-hidden shadow rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <dl>
          <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
            Bad Replies
          </dt>
          <dd class="mt-1 text-3xl leading-9 font-semibold text-gray-900">
            {{ $badReplies }}
          </dd>
        </dl>
      </div>
    </div>
    <div class="bg-white overflow-hidden shadow rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <dl>
          <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
            Revenue
          </dt>
          <dd class="mt-1 text-3xl leading-9 font-semibold text-gray-900">
            ${{ $revenue }}
          </dd>
        </dl>
      </div>
    </div>
  </div>
</div>
