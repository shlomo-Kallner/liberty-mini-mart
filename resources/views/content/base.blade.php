
@extends('master')

@php
    // put your code here
    //dd($navbar, $preheader, $cart);
    use \App\Utilities\Functions\Functions;
    use \App\Page;
    use \App\Cart;

    $testing = false;

    if (!Functions::testVar($navbar??'')) {
        $navbar = Page::getNavBar(false);
    }

    if (!Functions::testVar($preheader??'')) {
        $preheader = Page::getPreHeader(false);
    }

    if (!Functions::testVar($cart??'')) {
        $cart = Cart::getCurrentCart(null, true);
    }
    
@endphp

@include('lib.themewagon.fonts')
@include('lib.bootstrapious.fonts')

@include('lib.themewagon.css') 
@include('lib.bootstrapious.css')
@include('lib.bootstrapmade.css')


{{-- 
    UPDATE: Removing the @Include of 'inc.header_content'. 
            Moving fully over to 'lib.themewagon.nav' for 
            Navigational, Header & Footer Content.
--}}
@if ($testing)

@else

    
    @component('lib.themewagon.nav')
        @slot('navbar')
            {!! serialize($navbar) !!}
        @endslot
        @slot('preheader')
            {!! serialize($preheader) !!}
        @endslot
        @slot('cart')
            {!! serialize($cart) !!}
        @endslot
        @slot('currency')
            {{ $cart['currencyIcon']??'fa-usd' }}
        @endslot
    @endcomponent

    
@endif

@include('lib.bootstrapious.modals.login')
@include('lib.bootstrapious.modals.search')

@section('main-content')
    @parent

    @php
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

@endsection


{{-- BEGIN SECTION:  JS Content From Metronic Shop UI --}}
@include('lib.themewagon.js')
{{-- END SECTION:  JS Content From Metronic Shop UI --}}
{{-- BEGIN SECTION:  JS Content From UNIVERSAL --}}
{{-- _include('lib.bootstrapious.js') --}}
{{-- END SECTION:  JS Content From UNIVERSAL --}}


@section('cookie-cutter')   
@endsection