@extends('app')
@section('section_name', "Viewing Account $account->name")
@section('content')
@unless($account->is_group)
<div class="mt-8 bg-white py-5 shadow overflow-hidden sm:rounded-md">
  <div class="px-3 flex items-center justify-between flex-wrap sm:flex-no-wrap">
    <div class="flex inline-flex items-center">
      <div class="flex inline-flex items-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
        Numbers
        </h3>
        <span class="sm:ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
          {{ $account->numbers_count }} numbers
        </span>
        @if ($account->messageGroup)
          <a href="{{ route('message-groups.show', $account->messageGroup) }}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 hover:bg-blue-50 text-blue-800">
            Attached to MG {{ $account->messageGroup->name }}
          </a>
        @endif
      </div>
    </div>
    <span class="ml-3 shadow-sm rounded-md">
      <form method="POST" action="{{ route('numbers.store') }}" enctype="multipart/form-data">
        @csrf
        <input class="w-64" type="file" name="file" accept=".csv">
        <input type="hidden" name="account_id" value="{{ $account->id }}">
        <span class="shadow-sm rounded-md">
          <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
          Upload Numbers
          </button>
        </span>
      </form>
    </span>
  </div>
  @include('accounts.partials.numbers_table')
</div>
@endunless

@if($account->is_group)
  @include('accounts.partials.account-group')
@endif
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    $('.select2').select2();
});
</script>
@endsection
