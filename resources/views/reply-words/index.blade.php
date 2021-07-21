@extends('app')
@section('section_name', 'Reply Words')
@section('buttons_right')

@endsection
@section('content')
<div>

  @livewire('reply-words', ['type' => 'good'])
  @livewire('reply-words', ['type' => 'bad'])

</div>
@endsection
