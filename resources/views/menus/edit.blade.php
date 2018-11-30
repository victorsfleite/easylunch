@extends('layouts.app')

@section('content')
<div class="container">
    <menus-form :resource="{{ json_encode($menu) }}"></menus-form>
</div>
@endsection
