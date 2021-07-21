<div class="bg-white px-4 py-5 shadow overflow-hidden sm:rounded-md" wire:loading.class="opacity-50 disabled cursor-not-allowed" x-data="{ message_type: 'longcode', link_type: '{{ $linkType }}' }">
  <form method="POST" action="{{ $drip ? route('drips.store') : route('campaigns.store') }}">
    @csrf
    <div>
      <div>
        <div>
          <h3 class="text-lg leading-6 font-medium text-gray-900">
          {{ $drip ? 'Drip Information' : __('Campaign Information') }}
          </h3>
        </div>
        @include('errors')
        @unless ($drip)
          <div class="rounded-md bg-blue-50 p-4 my-4">
            <div class="flex">
                <h3 class="text-sm leading-5 font-medium text-blue-800">
                Messages to send: {{ $toSend }}
                </h3>
            </div>
          </div>
        @endif
        <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
          <div class="sm:col-span-6">
            <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
              Name
            </label>
            <div class="mt-1 rounded-md shadow-sm">
              <input wire:model.debounce.700ms="data.name" name="name" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
            </div>
          </div>
          @if ($drip)
            <div class="sm:col-span-2">
              <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
                Delay time (in hours)
              </label>
              <div class="mt-1 rounded-md shadow-sm">
                <input wire:model="data.drip_wait_hours" name="drip_wait_hours" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
              </div>
            </div>
            <div class="sm:col-span-2">
              <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
                Skip Weekends
              </label>
              <div class="mt-1 rounded-md shadow-sm">
                <select wire:model="data.drip_skip_weekends" name="drip_skip_weekends" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                  <option value="no">No</option>
                  <option value="yes">Yes</option>
                </select>
              </div>
            </div>
            <div class="sm:col-span-2">
              <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
                Time Limit (Local Time)
              </label>
              <div class="mt-1 rounded-md shadow-sm">
                <select wire:model="data.drip_time_limit" name="drip_time_limit" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                  <option value="">None</option>
                  @foreach (get_hours() as $hour)
                    <option value="{{ $hour }}">{{ $hour }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          @endif
          <div class="sm:col-span-3">
            <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
              Message Type
            </label>
            <div class="mt-1 rounded-md shadow-sm">
              <select wire:model="data.message_type" x-model="message_type" name="message_type" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                <option value="longcode">Longcode</option>
                <option value="keyword_reply">Keyword Reply</option>
              </select>
            </div>
          </div>
          <div class="sm:col-span-3">
            <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
              Link Type
            </label>
            <div class="mt-1 rounded-md shadow-sm">
              <select wire:model="linkType" x-model="link_type" name="link_type" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                <option value="hash">Hash</option>
                <option value="amazon">Amazon</option>
                <option value="no_link">No Link</option>
                {{-- <option value="hash">Hash</option> --}}
              </select>
            </div>
          </div>
              <div x-show="link_type == 'hash'" class="sm:col-span-6">
                <label name="provider" class="block text-sm font-medium leading-5 text-gray-700">
                  Domain Group
                </label>
                <div class="mt-1 rounded-md shadow-sm">
                  <select wire:model="domainGroupId" name="domain_group_id" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                    <option value="" selected>Select a Domain Group</option>
                    @foreach ($domainGroups as $domainGroup)
                      <option value="{{ $domainGroup->id }}">{{ $domainGroup->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
          <div class="sm:col-span-3">
            <label name="provider" class="block text-sm font-medium leading-5 text-gray-700">
              List
            </label>
            <div class="mt-1 rounded-md shadow-sm">
              <select wire:model="selectedList" wire:change="recalculateAmountToSend()" name="{{ $this->catalogFieldName }}" @if (!$drip) multiple @endif class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                <option selected>Select a List</option>
                @foreach ($lists as $list)
                  <option value="{{ $list->id }}">{{ $list->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="sm:col-span-3">
            <label name="provider" class="block text-sm font-medium leading-5 text-gray-700">
              Offer
            </label>
            <div class="mt-1 rounded-md shadow-sm">
              <select wire:model="data.offer_id" name="offer_id" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                @foreach ($offers as $offer)
                  <option value="{{ $offer->id }}">{{ $offer->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          @unless ($drip || $this->shouldNotShowFilters)
          <div class="sm:col-span-3">
            <label class="block text-sm font-medium leading-5 text-gray-700">
              Skip #
            </label>
            <span class="text-sm text-gray-500">
              If filled, the first x leads will be skipped.
            </span>
            <div class="mt-1 rounded-md shadow-sm">
              <input wire:model="skip" wire:keydown.debounce.500ms="recalculateAmountToSend" name="skip" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
            </div>
          </div>
          <div class="sm:col-span-3">
            <label class="block text-sm font-medium leading-5 text-gray-700">
              Amount of leads
            </label>
            <span class="text-sm text-gray-500">
              The total amount of leads for the system to pick up.
            </span>
            <div class="mt-1 rounded-md shadow-sm">
              <input wire:model="limit" wire:keydown.debounce.500ms="recalculateAmountToSend" name="limit" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
            </div>
          </div>
          @endif

          <div class="sm:col-span-3">
            <label name="provider" class="block text-sm font-medium leading-5 text-gray-700">
              Account
            </label>
            <div class="mt-1 rounded-md shadow-sm">
              <select wire:model="data.account_id" name="account_id" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                @foreach ($accounts as $account)
                  <option value="{{ $account->id }}">{{ $account->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="sm:col-span-3">
            <label name="provider" class="block text-sm font-medium leading-5 text-gray-700">
              <span x-show="message_type == 'longcode'">Message Group</span>
              <span x-show="message_type == 'keyword_reply'">First Message Group</span>
            </label>
            <div class="mt-1 rounded-md shadow-sm">
              <select wire:model="data.message_group_id" name="message_group_id" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                @foreach ($messageGroups as $messageGroup)
                  <option value="{{ $messageGroup->id }}">{{ $messageGroup->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          @if ($this->data['message_type'] == 'keyword_reply')
            <div class="sm:col-span-3">
              <label name="provider" class="block text-sm font-medium leading-5 text-gray-700">
                Reply Account
              </label>
              <div class="mt-1 rounded-md shadow-sm">
                <select wire:model="data.reply_account_id" name="reply_account_id" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                  @foreach ($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name }} — {{ $account->numbers_count }} numbers</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="sm:col-span-3">
              <label name="provider" class="block text-sm font-medium leading-5 text-gray-700">
                Reply Message Group
              </label>
              <div class="mt-1 rounded-md shadow-sm">
                <select wire:model="data.reply_message_group_id" name="reply_message_group_id" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                  @foreach ($replyMessageGroups as $messageGroup)
                    <option value="{{ $messageGroup->id }}">{{ $messageGroup->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          @endif

        </div>
      </div>
    </div>



    <div class="mt-6">
      <h3 class="text-lg py-2 border-b-2 border-gray-100">Carriers</h3>
<div class="sm:border-t sm:border-gray-200 sm:pt-5">
            <div role="group" aria-labelledby="label-email">
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">
                <div class="mt-4 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg">
                    @foreach (App\Carrier::all() as $key => $carrier)
                      <div class="@unless ($loop->first) mt-4 @endif">
                        <div class="relative flex items-start">
                          <div class="flex items-center h-5">
                            <input name="carriers[]" wire:model="carriers.{{ $key }}" wire:change="recalculateAmountToSend" type="checkbox" value="{{ $carrier->id }}" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                          </div>
                          <div class="ml-3 text-sm leading-5">
                            <label class="font-medium text-gray-700">{{ $carrier->name }}</label>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>
	<div class="mt-6">
      <h3 class="text-lg py-2 border-b-2 border-gray-100">
          Extra
          </h3>
	</div>	  
	<div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
		
		<div class="sm:col-span-3">
			<label name="name" class="block text-sm font-medium leading-5 text-gray-700">
			  Repliers List <em style="font-size: 10px;">( The list where all the positive reply leads are going to go. )</em>
			</label>
			<div class="mt-1 rounded-md shadow-sm">
			  <select wire:model="selectedRepliersList" wire:change="recalculateAmountToSend()" name="repliers_catalog_id" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                <option value="" selected>None</option>
                @foreach ($lists as $list)
                  <option value="{{ $list->id }}">{{ $list->name }}</option>
                @endforeach
              </select>
			</div>
		  </div>
		
		<div class="sm:col-span-3">
			<label name="name" class="block text-sm font-medium leading-5 text-gray-700">
			  24 Hours Rule
			</label>
			<div class="mt-1 rounded-md shadow-sm">
			  <select name="rule_24_hours" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
				<option value="1">Enable</option>
				<option value="0">Disable</option>
			  </select>
			</div>
		  </div>
		  
		  <div class="sm:col-span-3">
			<label name="name" class="block text-sm font-medium leading-5 text-gray-700">
			  Schedule
			</label>
			<div class="mt-1 rounded-md shadow-sm">
			  <input type="text" id="datepicker" name="campaign_send_date" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
			</div>
		  </div>
	</div>
	
    <div class="mt-8 border-t border-gray-200 pt-5">
      <div class="flex justify-end">
        <span class="ml-3 inline-flex rounded-md shadow-sm">
          <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out">
          Create Campaign
          </button>
        </span>
      </div>
    </div>
  </form>
</div>
