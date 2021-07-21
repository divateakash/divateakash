<tr wire:init="loadCounts" wire:poll.60000ms="loadCounts">
  <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200 block">
    <div class="text-sm leading-5 font-medium text-gray-900">{{ $campaign->name }}</div>
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
      {{ Str::studly($campaign->status) }}
    </span>
    <div class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-200 text-blue-800">@if ($readyToLoad) ${{ $campaign->amountSpent() }}@endif
    </div>
    @if ($campaign->skip)
      <div class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 text-red-800">Skipped the first {{ $campaign->skip }} leads
      </div>
    @endif
  </td>
  <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200">
    <div class="text-sm leading-5 text-gray-900">
      @if ($campaign->isDrip())
        {{ $campaign->drip_wait_hours }} hours
        @if ($campaign->drip_skip_weekends)
            <div class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 text-red-800">Skips weekends
            </div>
        @endif
        @if ($campaign->drip_time_limit)
            <div class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 text-red-800">Up to {{ $campaign->drip_time_limit }} (Local Time)
            </div>
        @endif
      @else
        {{ $campaign->created_at }}</div>
      @endif
  </td>
  <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200">
    <div>
      @if ($campaign->account)
        <a href="{{ route('accounts.show', $campaign->account) }}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 hover:bg-blue-200 text-blue-800">
          {{ $campaign->account->name }}
        </a>
      @endif
    </div>
    @if ($campaign->replyAccount)
      <div>
        <a href="{{ route('accounts.show', $campaign->replyAccount) }}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 hover:bg-green-200 text-green-800">
          {{ $campaign->replyAccount->name }}
        </a>
      </div>
    @endif
  </td>
  <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200">
    <div class="text-sm leading-5 text-gray-900">
      @if($campaign->catalog)
	  <a href="{{ route('lists.show', $campaign->catalog) }}" class="text-blue-700 hover:text-blue-900 font-semi  bold">{{ $campaign->catalog->name }}</a>
	  @endif
    </div>
    @if ($campaign->repliersCatalog)
      <div class="text-sm leading-5 text-gray-900">
        <a href="{{ route('lists.show', $campaign->repliersCatalog) }}" class="text-green-700 hover:text-green-900 font-semibold">Repliers: {{ $campaign->repliersCatalog->name }}</a>
      </div>
    @endif
  </td>
  <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200">
    <div class="text-sm leading-5 text-gray-900">
      @if ($campaign->messageGroup)
        <a href="{{ route('message-groups.show', $campaign->messageGroup) }}" class="text-blue-700 hover:text-blue-900 font-semibold">{{ $campaign->messageGroup->name }}</a>

        @if (!$campaign->messageGroup->activeMessages()->count())
          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 hover:bg-red-200 text-red-800">No Messages</span>
        @endif
      @endif
    </div>
    @if ($campaign->replyMessageGroup)
      <div class="text-sm leading-5">
        <span class="text-gray-500">reply:</span>
        <a href="{{ route('message-groups.show', $campaign->replyMessageGroup) }}" class="text-blue-700 hover:text-blue-900 font-semibold">{{ $campaign->replyMessageGroup->name }}</a>

        @if (!$campaign->replyMessageGroup->activeMessages()->count())
          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 hover:bg-red-200 text-red-800">No Messages</span>
        @endif
      </div>
    @endif
  </td>
  <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ Str::studly($campaign->link_type) }}</span>
    @if ($campaign->link_type == 'hash' && $campaign->domainGroup)
      <br>
      <a href="{{ route('domain-groups.show', $campaign->domainGroup) }}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 hover:bg-red-200 text-red-800">Domain Group: {{ $campaign->domainGroup->name }}
      </a>
    @endif
  </td>
  <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200">
    <div class="text-sm leading-5 text-gray-900">@if($readyToLoad) {{ $campaign->sent_outbounds_count }} @endif</div>
    <div class="text-sm leading-5 text-gray-500">@if($readyToLoad) of {{ $campaign->outbounds_count }} @endif</div>
    @if ($readyToLoad && $campaign->message_type == 'keyword_reply')
      <a href="{{ route('campaigns.pending-replies.index', $campaign) }}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">{{ $campaign->pending_replies_outbounds_count}} replies pending</a> <br>
      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $campaign->sent_reply_outbounds_count }} replies sent</span> <br>
      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ $campaign->delivered_reply_outbounds_count }} replies delivered</span>
    @endif
  </td>
  <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200">
    <div class="text-sm leading-5 text-gray-900"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ $campaign->failed_outbounds_count }}</span></div>
  </td>
  <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200">
    <div class="text-sm leading-5 text-gray-900">@if($readyToLoad) {{ $campaign->replies_count }} @endif</div>
    <div class="text-sm leading-5 text-gray-900 mt-2"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">@if($readyToLoad) {{ $campaign->good_replies_count }} @endif</span></div>

  </td>
  <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200">
    @if ($campaign->usesHash())
      <div class="text-sm leading-5 text-gray-900"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ $campaign->clicks_count }}</span></div>
    @else
      <div class="text-sm leading-5 text-gray-900"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Unavailable</span></div>
    @endif
  </td>
  <td class="px-3 py-4 whitespace-no-wrap border-b border-gray-200">
      @if ($campaign->usesAmazonLinks() || $campaign->usesHash())
        <div class="text-sm leading-5 text-gray-900"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $campaign->conversions->count() }}</span></div>
        <div class="mt-2 text-sm leading-5 text-gray-900"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">${{ $campaign->conversions->sum('revenue') }}</span></div>
      @else
        <div class="text-sm leading-5 text-gray-900"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Unavailable</span></div>
      @endif
  </td>
  <td class="px-3 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
    <a href="{{ route('campaigns.outbounds.index', $campaign) }}" class="text-blue-600 hover:text-blue-900">Outbounds</a>
    <a href="{{ route('campaigns.replies.index', $campaign) }}" class="ml-2 text-blue-600 hover:text-blue-900">Replies</a>
    @if ($campaign->status == 'paused')
      <button wire:click="toggleStatus" class="ml-2 inline-flex items-center px-6 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-green-600 hover:bg-green-500 focus:outline-none focus:shadow-outline-green focus:border-green-700 active:bg-green-700 transition duration-150 ease-in-out">Resume</button>
    @elseif ($campaign->status === 'sending')
      <button wire:click="toggleStatus" class="ml-2 inline-flex items-center px-6 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">Pause</button>
    @endif

    @if ($campaign->status == 'paused')
      <button wire:click="finishCampaign" class="ml-2 inline-flex items-center px-6 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-red-600 hover:bg-red-500 focus:outline-none focus:shadow-outline-red focus:border-red-700 active:bg-red-700 transition duration-150 ease-in-out">Finish</button>
    @endif

    @if ($campaign->isDrip())
      <a href="{{ route('drips.create', ['source' => $campaign->uuid]) }}" class="ml-2 text-green-600 hover:text-green-900">Duplicate</a>
    @else
      <a href="{{ route('campaigns.create', ['source' => $campaign->uuid]) }}" class="ml-2 text-green-600 hover:text-green-900">Duplicate</a>
    @endif
  </td>
</tr>
