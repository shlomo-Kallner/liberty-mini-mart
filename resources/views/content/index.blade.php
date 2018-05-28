<?php
// Will be placing our "index" or "Home" page content in this view/file.
    use \App\Utilities\Functions\Functions;

?>

@extends('content.template')

@section('css-extra-fonts')
@parent

{{-- the font to be placed in a yield or in a child (extending) view.. --}}
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900&amp;subset=all" rel="stylesheet" type="text/css">
<!--- fonts for slider on the index page -->  

@endsection


@section('css-preloaded-local')
{{-- page local css --}}
@parent


@endsection


@section('main-content')
@parent
@php
    $new_products2 = Functions::getBladedContent($newProducts??'');
    $pricing2 = Functions::getBladedContent($pricing??'');
    $sidebar2 = Functions::getUnBladedContent($sidebar??'');
@endphp

@component('lib.themewagon.new_products')
    @slot('sidebar')
        {!! serialize($sidebar2) !!}
    @endslot
    @slot('newProducts')
        {!! serialize($new_products2) !!}
    @endslot

    @if(Functions::testVar($new_products2))
        @foreach ($new_products2 as $key => $item)
            @slot($key)
                {{$item}}
            @endslot    
        @endforeach

    @endif
@endcomponent

@component('lib.themewagon.article')
    @foreach ($page as $key => $item)
        @slot($key)
            {{ $item }}
        @endslot
    @endforeach
@endcomponent



@component('lib.bootstrapmade.pricing')
    @if(Functions::testVar($pricing2))

        @foreach ($pricing2 as $key => $item)
            @slot($key)
                {{$item}}
            @endslot    
        @endforeach
    
    @endif
@endcomponent

@endsection

@section('js-defered')
    @parent

@endsection



