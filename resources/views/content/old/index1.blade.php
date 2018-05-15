<?php
// Will be placing our "index" or "Home" page content in this view/file.
?>

@extends('master')

@section('css-extra-fonts')
@include('..inc.index_extra_content')
@endsection

{{--@section('main-content')
@parent
<div class="row">
    <div class="col-md-12">
        <h1>{!! $page['header'] !!} </h1>
        <div>
            {!! $page['article'] !!}
        </div>
    </div>
</div>
@endsection--}}
