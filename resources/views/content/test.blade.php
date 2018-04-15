@extends('master')

@section('main-content')
<div class="row">
    <div class="col-md-12">
        <h1>Welcome to: <a href="{{ url('') }}">{{ $siteName }}</a> </h1>
        <p>
            This is: {{ $requestedPage }}.
        </p>
    </div>
</div>
@endsection
