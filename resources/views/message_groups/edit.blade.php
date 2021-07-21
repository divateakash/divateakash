@extends('app')
@section('section_name', 'New Message Group')
@section('buttons_right')
<span class="ml-3 shadow-sm rounded-md">
  <a href="{{ route('message-groups.index') }}" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-500 focus:outline-none focus:shadow-outline-gray focus:border-gray-700 active:bg-gray-700 transition duration-150 ease-in-out">
    {{ __('Go Back') }}
  </a>
</span>
@endsection
@section('content')
<div class="bg-white px-4 py-5 shadow overflow-hidden sm:rounded-md">

<form method="POST" action="{{ $messageGroup->id ? route('message-groups.update', $messageGroup) : route('message-groups.store') }}">
  @csrf
  @if ($messageGroup->id)
    @method('PUT')
  @endif
  <div>
    <div>
      <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">
        Message Group Information
        </h3>
      </div>
      <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
        <div class="sm:col-span-6">
          <label name="name" class="block text-sm font-medium leading-5 text-gray-700">
            Name
          </label>
          <div class="mt-1 rounded-md shadow-sm">
            <input name="name" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" value="{{ $messageGroup->name }}" />
          </div>
        </div>
      </div>

      <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
        <div class="sm:col-span-6">
          <label class="block text-sm leading-5 font-medium text-gray-700">Type
          </label>
          <select name="type" class="mt-1 form-select block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5">
            <option value="First" @if($messageGroup->type == 'First') selected @endif>First Message</option>
            <option value="Reply" @if($messageGroup->type == 'Reply') selected @endif>Reply Message</option>
          </select>
        </div>
      </div>

      <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
        <div class="sm:col-span-6">
          <div class="relative flex items-start">
            <div class="absolute flex items-center h-5">
              <input type="checkbox" name="single_message_only" class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" @if ($messageGroup->single_message_only) checked @endif>
            </div>
            <div class="pl-7 text-sm leading-5">
              <label for="comments" class="font-medium text-gray-700">Single Message Only
              </label>
              <p class="text-gray-500">The system will only use a single message of the stack. <br> If you are not sure about this option, leave this unmarked.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="mt-8 border-t border-gray-200 pt-5">
    <div class="flex justify-end">
      <span class="ml-3 inline-flex rounded-md shadow-sm">
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out">
        Add Message Group
        </button>
      </span>
    </div>
  </div>
</form>


</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
$('.members-select').select2();
});
</script>
@endsection
