@extends('layouts.app')

@section('content')
<div class="container">
    <menus-index date="{{ request()->date }}"></menus-index>
</div>
@endsection
