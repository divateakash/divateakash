@php
  $filteredMessages = $trashed ? $messages->filter->trashed() : $messages->reject->trashed();
@endphp

<table class="min-w-full" x-show="trashed == {{ $trashed ? 'true' : 'false' }}">
  <thead>
    <tr>
      <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
        Content
      </th>
      <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
        Created At
      </th>
      @if($trashed)
        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
          Deleted At
        </th>
        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
          Reason
        </th>
      @endif
      @unless ($trashed)
      <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
      @endunless
    </tr>
  </thead>
  <tbody class="bg-white">
    @foreach($filteredMessages as $message)
    <tr>
      <td class="px-6 py-4 whitespace-normal border-b border-gray-200 block">
        <div class="text-sm leading-5 font-medium text-gray-900">{{ $message->content }}</div>
      </td>
      <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
        <div class="text-sm leading-5 font-medium text-gray-900">{{ $message->created_at }}</div>
      </td>
      @if($trashed)
            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
              <div class="text-sm leading-5 font-medium text-gray-900">{{ $message->deleted_at }}</div>
            </td>
            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
              <div class="text-sm leading-5 font-medium text-gray-900">{{ $message->delete_reason }}</div>
            </td>
      @endif
      @unless ($trashed)
      <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
        <form method="POST" action="{{ route('messages.destroy', $message) }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
        </form>
      </td>
      @endunless
    </tr>
    @endforeach
  </tbody>
</table>
