@extends('layouts.app')

@section('content')
<div class="container">
    <menu-show :menu="{{ json_encode($menu) }}"></menu-show>
</div>
@endsection
