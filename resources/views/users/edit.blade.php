@extends('layouts.app')

@section('content')
<div class="container">
    <users-form :resource="{{ json_encode($user) }}"></users-form>
</div>
@endsection
