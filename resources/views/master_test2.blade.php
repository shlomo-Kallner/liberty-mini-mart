<?php
// put your code here
/// load 50X and 404 page's dynamic menu here...
/// Don't forget to pass the variable to the sub-view!!
//use App\Page;
//$nav = Page::getNavBar();
$title .= '-- dummy Title -- for testing Master Page 2';
?>
<!DOCTYPE html>
@section('license-header')
<!--

My license header. 
Copyright 2018 Shlomo Kallner , shlomo.kallner@gmail.com

-->
@include('lib.themewagon.license')
@show


{{--
"lifting" some IE conditional comments from Metronic...
Although i'm not quite sure if it will effect material from other templates... 
and anyways, who still uses IE8?
******
UPDATE(23/04/2018): discovered that the other template pages use the 'no-js' css class for IE9 and below as well.
--}}
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html>
    <!--<![endif]-->
    <head>
        <meta charset="UTF-8">
        <!--        "lifting" the http-equiv from Metronic too...-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <!--        Common "standard" viewport meta..-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        @section('header-metas')
        <meta content="Metronic Shop UI description" name="description">
        <meta content="Metronic Shop UI keywords" name="keywords">
        <meta content="Shlomo Kallner" name="author">

        <meta property="og:site_name" content="{{ $siteName }}">
        <meta property="og:title" content="-CUSTOMER VALUE-">
        <meta property="og:description" content="-CUSTOMER VALUE-">
        <meta property="og:type" content="website">
        <meta property="og:image" content="-CUSTOMER VALUE-"><!-- link to image for socio -->
        <meta property="og:url" content="{{ url('') }}">
        @show

        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        <title>{{ $title }}</title>

        @include('inc.js.compatibility') <!-- <== from bootstrap and others...-->

        <!-- CSS START -->
        <!-- Fonts START -->
        @section('css-fonts')
        {{-- @include('inc.css.fonts') --}}
        {{-- 
            These "_Section ... _Include ... _Show" bother me.
            I dont think that I should be writing code blocks 
            like this...
            So, the plan is to phase them out..
            (though just the '_Include' part..),
            moving the content of the _Include to the _Extending
            Views..
        --}}
        <!-- Global Fonts from Metronic Shop UI START -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|PT+Sans+Narrow|Source+Sans+Pro:200,300,400,600,700,900&amp;subset=all" rel="stylesheet" type="text/css">
        <!-- Global Fonts from Metronic Shop UI END -->
        @show
        <!-- Fonts END -->

        @section('css-extra-fonts')

        @show

        @section('css-preloaded')
        @include('inc.css.preloaded')
        @show
        <!-- CSS END -->


        <!-- Preloaded JS START... -->
        @section('js-preloaded')
        @include('inc.js.preloaded')
        @show
        <!-- Preloaded JS END -->
    </head>
    <body @yield('body-tags') >{{-- note: only Metronic uses this taging.. --}}
           <header>
            @section('header-content')
            @include('inc.header_content')
            @show
        </header>
        <br><br><br>
        <main>
            <div class="container">
                @section('main-content')
                @show
            </div>  
        </main>
        <br><br><br>
        <footer>
            @section('footer-content')
            @show
        </footer>
        @section('css-defered')
        @include('inc.css.defered')
        @show

        @section('js-defered')
        @include('inc.js.defered')
        @show
    </body>
</html>
