<?php
// put your code here
/// load 50X and 404 page's dynamic menu here...
/// Don't forget to pass the variable to the sub-view!!
//use App\Page;
//$nav = Page::getNavBar();
//$title = "dummy Title";
?>
<!DOCTYPE html>
<?php
//
// Testing the injection of an 
// 'empty' or non-existent section ...
// using "@yield('babble-section')" blade syntax..
//
// => Successfully! 
// Results: non-existent or empty section is 
//          not displayed!!
//          
/// 

/** Testing the use of "@include('<someFileName>')" 
 *  within a "@section" and "@yield"-ing 
 *  as well not "@yield"-ing the section...
 *  using syntax "@yield('license-header')"
 *  
 *  => Successfully! 
 *  Results: "@include"-d content is 
 *           displayed/inserted 
 *           based on whether or not 
 *           the "@section" is "@yield"-ed
 *           or not.
 * 
 * <script>console.log("Before yield");</script>
  <!--yield('license-header')-->
  <script>console.log("After yield");</script>

 * 
 * */
?>
{{--extends('master')--}}

@section('license-header')
@include('inc.license')
@show

{{--
"lifting" some IE conditional comments from Metronic...
Although i'm not quite sure if it will effect material from other templates... 
and anyways, who still uses IE8?
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

        @yield('header-metas')

        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        <title>{{ $title }}</title>
        @include('inc.js.compatibility') <!-- <== from bootstrap and others...-->
        @section('css-fonts')
        @include('inc.css.fonts')
        @show

        @section('css-extra-fonts')

        @show

        @section('css-preloaded')
        @include('inc.css.preloaded')
        @show

        @section('js-preloaded')
        @include('inc.js.preloaded')
        @show
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
            @include('inc.footer_content')
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
