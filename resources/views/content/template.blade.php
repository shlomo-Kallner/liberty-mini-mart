<?php
// put your code here
?>

@extends('master_test2')

@include('lib.themewagon.fonts')
@include('lib.bootstrapious.fonts')

@include('lib.themewagon.css') 
@include('lib.bootstrapious.css')
@include('lib.bootstrapmade.css')


@section('body-tags')
class="ecommerce"
@endsection

{{-- 
    UPDATE: Removing the @Include of 'inc.header_content'. 
            Moving fully over to 'lib.themewagon.nav' for 
            Navigational, Header & Footer Content.
--}}

@include('lib.themewagon.nav')

@include('lib.bootstrapious.modals.login')
@include('lib.bootstrapious.modals.search')

@section('main-content')
@parent
@component('lib.themewagon.breadcrumbs')
    @slot('breadcrumbs')
        {{serialize($page['breadcrumbs'])}}
    @endslot
@endcomponent
<div class="row">
    <div class="col-md-5">
        <h1>{!! $page['header'] !!} </h1>
        <h2>
        {!! $page['subheading'] !!}
        </h2>
        
        @if (isset($page['img']))
            <img src="{{ $page['img'] }}" alt="{{ $page['imgAlt'] }}">
        @endif
        
        {{-- <i class="fa fa-search" style="font-size: 16px;"></i> --}}
        
    </div>
    <div class="col-md-5">
        <div>
            {!! $page['article'] !!}
        </div>
    </div>
</div>

@endsection


@section('footer-content')
@parent
@include('lib.themewagon.prefooter')
@include('inc.copyrights')
@component('lib.themewagon.product_fast_view')
    
@endcomponent
@endsection


{{-- BEGIN SECTION:  JS Content From Metronic Shop UI --}}
@include('lib.themewagon.js')
{{-- END SECTION:  JS Content From Metronic Shop UI --}}

@section('cookie-cutter')



    
@endsection