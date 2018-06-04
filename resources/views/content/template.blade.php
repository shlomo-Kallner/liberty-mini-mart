<?php
// put your code here
?>

@extends('master')

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
            {!! serialize($breadcrumbs) !!}
        @endslot
    @endcomponent

    @if (false)
        @component('lib.themewagon.article')
            @foreach ($page as $key => $item)
                @slot($key)
                    {{ $item }}
                @endslot
            @endforeach
        @endcomponent
    @endif

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
{{-- BEGIN SECTION:  JS Content From UNIVERSAL --}}
@include('lib.bootstrapious.js')
{{-- END SECTION:  JS Content From UNIVERSAL --}}


@section('cookie-cutter')   
@endsection