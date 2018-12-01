@extends('layouts.app')

@section('content')
<div class="container">
    <orders-form :resource="{{ json_encode($order) }}"></orders-form>
</div>
@endsection
