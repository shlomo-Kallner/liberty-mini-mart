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

    @php
        use \App\Utilities\Functions\Functions;
        $pageHeader2 = Functions::getBladedString($page['header']??'','');
    @endphp

    @if (Functions::testVar($pageHeader2))
    
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="page-header">
                    <h1 class="text-center">
                        {!! $pageHeader2 !!}
                    </h1>
                </div>
            </div>
        </div>

    @endif

    @component('lib.themewagon.breadcrumbs')
        @slot('breadcrumbs')
            {!! serialize($breadcrumbs??'') !!} 
        @endslot
    @endcomponent

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