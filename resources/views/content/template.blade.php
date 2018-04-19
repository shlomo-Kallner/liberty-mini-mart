@extends('old-master')


@section('babble-section')
<!-- Testing the injection of an 'empty' or non-existent section => successfully! 
Results: non-existent or empty section is not displayed!! -->
@endsection


@section('main-content')
@parent
<div class="row">
    <div class="col-md-12">
        <h1>{!! $page['header'] !!} </h1>
        <div>
            {!! $page['article'] !!}
        </div>
    </div>
</div>
@endsection
