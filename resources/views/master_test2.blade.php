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
@include('lib.themewagon.license'){{--  <-- This file is OK, its pure HTML. --}}
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

        @include('inc.js.compatibility') {{-- <== from bootstrap and others...
                                                its all in its own file in case
                                                of possible 'growth'...
                                                p.s. its just some IE Conditionals
                                                     and a Blade comment..
                                          --}}

        <!-- CSS START -->
        <!-- Fonts START -->
        @section('css-fonts')
        {{-- This @Section was copied from 'lib.themewagon.fonts'
            instead @Including it here or in a child view. --}}
        {{-- 
            These "_Section ... _Include ... _Show" bother me.
            I dont think that I should be writing code blocks 
            like this...
            So, the plan is to phase them out..
            by moving the content of the _Include to the _Extending
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

        @yield('css-preloaded-global')

        @yield('css-preloaded-local')

        @yield('css-themes')


        {{-- In the _Extending views place the @Parent directive LAST!! --}}
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}" type="text/css">
        @show
        <!-- CSS END -->


        <!-- Preloaded JS START... -->
        @section('js-preloaded')
        @include('inc.js.preloaded'){{--  <-- This file is pure HTML.. --}}
        @show
        <!-- Preloaded JS END -->
    </head>
    <body class="ecommerce"> {{-- " @yield('body-tags')" note: only Metronic uses this taging.. --}}
        @section('pre-header')
        @yield('pre-header-navbar')
        @show
        <header class="header">
            @section('header-content')

            @yield('header-navbar')

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
        @show

        @section('js-defered')
        <script src="{{ asset('lib/history.js/scripts/bundled/html4+html5/jquery.history.js') }}"></script>
        <!-- 
            this one is ours.. so it should come last.. 
            In the @Extending View - call @Parent last! 
        -->
        <script src="{{ asset('js/script.js') }}" type="text/javascript"></script>
        @show
    </body>
</html>
