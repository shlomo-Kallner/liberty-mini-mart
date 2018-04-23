@extends('master')

@section('main-content')
<div class="row">
    <div class="col-md-12">
        <h1>{!! $page['header'] !!} </h1>
        <div>
            {!! $page['article'] !!}
        </div>
    </div>
</div>
@endsection
