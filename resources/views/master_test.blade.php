<?php
// put your code here
/// load 50X and 404 page's dynamic menu here...
/// Don't forget to pass the variable to the sub-view!!
//use App\Page;
//$nav = Page::getNavBar();
$title .= '-- dummy Title -- blahhh';
?>
@extends('old-master')


@section('license-header')
@parent
@include('lib.themewagon.license')
@endsection

@section('header-metas')
<meta content="Metronic Shop UI description" name="description">
<meta content="Metronic Shop UI keywords" name="keywords">
<meta content="keenthemes" name="author">

<meta property="og:site_name" content="-CUSTOMER VALUE-">
<meta property="og:title" content="-CUSTOMER VALUE-">
<meta property="og:description" content="-CUSTOMER VALUE-">
<meta property="og:type" content="website">
<meta property="og:image" content="-CUSTOMER VALUE-"><!-- link to image for socio -->
<meta property="og:url" content="-CUSTOMER VALUE-">
@endsection

<!-- Fonts START -->
@include('lib.themewagon.fonts')

<!-- Fonts END -->

<!-- CSS START -->
@section('css-preloaded')

@include('lib.themewagon.css') 

@yield('css-preloaded-global')

@yield('css-preloaded-local')

@yield('css-themes')

@parent
@endsection

<!-- CSS END -->

@section('js-preloaded')

@parent
@endsection

@section('body-tags')
class="ecommerce"
@endsection

@section('header-content')
@parent
@endsection

@section('main-content')
@parent
<div class="row">
    <div class="col-md-5">
        <h1>{!! $page['header'] !!} </h1>
        <div>
            {!! $page['article'] !!}
        </div>
    </div>
    <div class="col-md-5">
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Modi, nulla, porro facilis officiis sequi natus eum nemo totam eius deserunt reprehenderit ducimus quia et itaque animi nostrum adipisci accusantium. Quaerat, eos ipsum expedita totam dolorem rem reiciendis voluptatibus quia dolor quam natus id ipsam aliquam fugiat ullam quibusdam unde corporis minima debitis odit laborum numquam repellat illo ea aut mollitia alias? Ut, facere, inventore, mollitia consectetur cum repellat quidem qui itaque modi quam laudantium cupiditate a nemo officia deserunt laboriosam temporibus unde voluptate suscipit labore voluptates cumque quas natus non in maiores dicta delectus omnis aut commodi animi molestiae amet fugit? Tenetur, eligendi, a pariatur laboriosam aliquid cum voluptate nisi laudantium officiis in voluptatum nihil libero consequatur tempora sunt dolorum beatae dicta quod illo impedit!
        </p>
    </div>
</div>

@endsection

@section('footer-content')
@parent

@endsection

@section('css-defered')
@parent

@endsection

{{-- BEGIN SECTION:  JS Content From Metronic Shop UI --}}
@include('lib.themewagon.js')
{{-- END SECTION:  JS Content From Metronic Shop UI --}}

{{-- WARNING!! CANNOT HAVE EMPTY NAMED SECTIONS! 
    (section-s with an empty string for a name 
     - can't even use the '@' symbol with a Blade reserved word's
    in a comment either!) 
    Laravel's Blade WILL BARF ON IT!!!

@section('')
@parent

@endsection

@section('')
@parent

@endsection--}}

