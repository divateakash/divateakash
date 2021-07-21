<form method="POST" action="{{ route('messages.store') }}">
  @csrf
  <input type="hidden" name="message_group_id" value="{{ $messageGroup->id }}">
  <div>
    <div>
      <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">
        New Message on group {{ $messageGroup->name }}
        </h3>
      </div>
      <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
        <div class="sm:col-span-6">
          <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
            Content
          </label>
          <div class="mt-1 rounded-md shadow-sm">
            <textarea name="content" wire:model="content" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"></textarea>
          </div>
          <div class="mt-2">
            {{ $this->characterCount }}/{{ $maxChars }}

            @if ($this->overMaxChars)
              <div class="rounded-md bg-red-50 p-4 mt-2">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <h3 class="text-sm leading-5 font-medium text-red-800">
                      Your message is over the max characters count.
                    </h3>
                  </div>
                </div>
              </div>
            @endif

          </div>
          <div class="mt-2">
            <div class="relative flex items-start">
              <div class="flex items-center h-5">
                <input type="checkbox" name="variations" class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
              </div>
              <div class="ml-3 text-sm leading-5">
                <label for="comments" class="font-medium text-gray-700">Unique Variations</label>
                <p class="text-gray-500">Create all possible spins as unique messages. ({{ $this->variationsCount }} variations, {{ $messageGroup->availableMessagesCount()}} message slots available)</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="mt-8 border-t border-gray-200 pt-5">
    <div class="flex justify-end">
      <span class="ml-3 inline-flex rounded-md shadow-sm">
        <button type="submit" @if ($this->overMaxChars || $this->overMaxMessages) disabled @endif type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 @if ($this->overMaxChars || $this->overMaxMessages) opacity-50 @endif focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out">
        Add Message
        </button>
      </span>
    </div>
  </div>
</form>
