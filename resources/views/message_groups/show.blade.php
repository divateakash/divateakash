@extends('app')
@section('section_name', "Viewing Message Group $messageGroup->name")
@section('content')
<div class="bg-white py-5 shadow overflow-hidden sm:rounded-md" x-data="{ trashed: false }">
  <div class="px-3 flex items-center justify-between flex-wrap sm:flex-no-wrap">
    <div class="sm:flex sm:inline-flex">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
      Message Groups
      </h3>
      <div class="sm:ml-3">
        <a href="{{ route('message-groups.messages.create', $messageGroup) }}" class="text-blue-600 hover:text-blue-500">Add a single message</a>
      </div>
    </div>

    <span class="mt-3 sm:mt-0 sm:ml-3 shadow-sm rounded-md flex inline-flex items-center">
      <span>Deleted</span>
      <span role="checkbox" tabindex="0" x-on:click="trashed = !trashed" @keydown.space.prevent="trashed = !trashed" :aria-checked="trashed.toString()" aria-checked="true" x-bind:class="{ 'bg-gray-200': !trashed, 'bg-blue-600': trashed }" class="ml-4 relative inline-block flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline bg-blue-600">
        <span aria-hidden="true" x-bind:class="{ 'translate-x-5': trashed, 'translate-x-0': !trashed }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200 translate-x-5"></span>
      </span>
      <form class="ml-4" method="POST" action="{{ route('messages.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="message_group_id" value="{{ $messageGroup->id }}">
        <input class="w-64" type="file" name="file" accept=".csv">
        <span class="shadow-sm rounded-md">
          <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
          Upload Messages
          </button>
        </span>
      </form>
    </span>
  </div>
  <div class="px-3">
    @if ($messageGroup->isFirstMessage())
      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
        First Message
      </span>
    @elseif ($messageGroup->isReplyMessage())
      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
        Reply Message
      </span>
    @endif
    @if ($messageGroup->single_message_only)
      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
        Single Message Only
      </span>
    @endif
  </div>
  <div class="mt-4 flex flex-col">
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-6">
      <div class="align-middle inline-block min-w-full  overflow-hidden sm:rounded-lg">
        @include('message_groups.partials.messages_table', ['trashed' => false])
        @include('message_groups.partials.messages_table', ['trashed' => true])
      </div>
    </div>
  </div>
</div>
@endsection
