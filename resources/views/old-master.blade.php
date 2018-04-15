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
// => Successfully! 
// Results: non-existent or empty section is 
//          not displayed!!
//
?>
@yield('babble-section')
@include('inc.license')


<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html>
    <!--<![endif]-->
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        <title>{{ $title }}</title>
        @include('inc.js.compatibility')
        @include('inc.css.fonts')
        @include('inc.css.preloaded')
        @include('inc.js.preloaded')
    </head>
    <body>
        <header>
            @include('inc.header_content')
        </header>
        <br><br><br>
        <main>
            <div class="container">
                @yield('main-content')
            </div>  
        </main>
        <br><br><br>
        <footer>
            @include('inc.footer_content')
        </footer>
        @include('inc.css.defered')
        @include('inc.js.defered')
    </body>
</html>
