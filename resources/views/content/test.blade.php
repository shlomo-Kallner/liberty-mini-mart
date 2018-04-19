@extends('master')

@section('header-content')
@parent
@endsection

@section('main-content')
@parent
<div class="row">
    <div class="col-md-5">
        <h1>Welcome to: <a href="{{ url('') }}">{{ $siteName }}</a> </h1>
        <p>
            This is: {{ $requestedPage }}.
        </p>
    </div>
</div>
@endsection
