<?php

    // put any special code here..
    use \App\Utilities\Functions\Functions;
    $title2 =  Functions::getBladedString($title ?? '');// . '-- dummy Title -- for testing Master Page 2';
    $siteName2 = Functions::getBladedString($site['name']?? App\Http\Controllers\MainController::$data['site']['name']);
    $scripts = Functions::getContent($site['scripts']??'');
    $usingCDNs = Functions::getBladedString($site['usingCDNs']??'');
    $usingMix = Functions::getBladedString($site['usingMix']??'');
    $nut2 = Functions::getContent($site['nut']??'');
    $alert2 =  Functions::getContent($alert??'');
?>

<!DOCTYPE html>
@section('license-header')
    <!--

        My license header. 
        Copyright {{ date('Y') }} Shlomo Kallner , shlomo.kallner@gmail.com

    -->

    @include('inc.licenses')


@show


{{--
    "lifting" some IE conditional comments from Metronic...
    Although im not quite sure if it will effect material 
    from other templates... 
    and anyways, who still uses IE8?
    ******
    UPDATE(23/04/2018): discovered that the other template pages 
    use the 'no-js' css class for IE9 and below as well.
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
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <script>
                window.Laravel = { 
                    csrfToken: '{{ csrf_token() }}',
                    upPngPath : "{{ url('lib/themewagon/metronicShopUI/theme/assets/corporate/img/up.png') }}",
                    alert: '@json($alert2)',
                    nut: '{{ $nut2 }}',
                    page: {},
                    setAlert: function (data) {
                        this.alert = data;
                    }
                };
            </script>
            <meta content="Metronic Shop UI description" name="description">
            <meta content="Metronic Shop UI keywords" name="keywords">
            <meta content="Shlomo Kallner" name="author">

            <meta property="og:site_name" content="{{ $siteName2 }}">
            <meta property="og:title" content="-CUSTOMER VALUE-">
            <meta property="og:description" content="-CUSTOMER VALUE-">
            <meta property="og:type" content="website">
            <meta property="og:image" content="-CUSTOMER VALUE-"><!-- link to image for socio -->
            <meta property="og:url" content="{{ url('') }}">
        @show

        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        <title>
            @if (Functions::testVar($title2))
                {{ $title2 }}
            @else
                @yield('pageTitle')
            @endif
        </title>

        
        <!-- CSS START -->
            <!-- Fonts START -->
                @section('css-fonts')
                    {{-- This @Section was copied from 'lib.themewagon.fonts'
                        originally, now @Including it in a child view
                        instead of here.. 
                    --}}
                    {{-- 
                        These "_Section ... _Include ... _Show" bother me.
                        I dont think that I should be writing code blocks 
                        like this...
                        So, the plan is to phase them out..
                        by moving the content of the _Include to the _Extending
                        Views..
                    --}}
                @show
            <!-- Fonts END -->

            @section('css-extra-fonts')

            @show

            @section('css-preloaded')

                @if (Functions::testVar($usingCDNs))
                    @yield('css-cdn-files')
                @endif

                @yield('css-preloaded-global')

                @yield('css-preloaded-local')

                @yield('css-themes')


                {{-- In the _Extending views place the @Parent directive LAST!! --}}
            @show

            <link rel="stylesheet" href="{{ asset('css/styles.css') }}" type="text/css">
        <!-- CSS END -->


        <!-- Preloaded JS START... -->
            {{-- 
                Loading compatibitity javascript tags 
                via _Blade:_Section 'js-defered' below..

                UPDATE: checked html5shiv project page at 
                https://www.npmjs.com/package/html5shiv
                and it specifies the placement of the 
                html5shiv script tag in the head tag...
            --}}
            @include('inc.js.compatibility')

            {{-- <== 'inc.js.compatibility' is from 
                        bootstrap and others...
                        its all in its own file in case
                        of possible 'growth'...
                        p.s. its just some IE Conditionals
                        and a Blade comment..
            --}}

            @section('js-preloaded')
                {{-- Not using Font Awesome v5! --}}
                {{-- Include('inc.js.preloaded') --}}{{--  <-- This file is pure HTML.. --}}
            @show
        <!-- Preloaded JS END -->
    </head>
    <body class="ecommerce"> 
        {{-- " @yield('body-tags')" note: only Metronic uses this ['ecommerce'] taging.. --}}

        <header>

            <nav class="navbar navbar-default" role="navigation">

                @section('pre-header')

                    @yield('pre-header-navbar')
    
                @show
    
                @section('header-content')
    
                    @yield('header-navbar')
    
                @show

                @if (isset($errors) && $errors->any())
                    @component('lib.site.alert')
                        @slot('class')
                            {!! 'alert-danger' !!}
                        @endslot
                        @slot('title')
                            {!! 'Errors Detected!' !!}
                        @endslot
                        @slot('content')
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endslot
                        @slot('timeout')
                            {!! '90000' !!}
                        @endslot
                    @endcomponent
                @else
                    @component('lib.site.alert')
                        @slot('class')
                            {!! $alert2['class']??'' !!}
                        @endslot
                        @slot('title')
                            {!! $alert2['title']??'' !!}
                        @endslot
                        @slot('content')
                            {!! $alert2['content']??'' !!}
                        @endslot
                        @slot('timeout')
                            {!! $alert2['timeout']??9000 !!}
                        @endslot
                    @endcomponent
                @endif

                @section('extra-navigation-content')
                    
                @show
        
            </nav>

        </header>

        @section('modals')

            @yield('login-modal')
            @yield('search-modal')
            @yield('user-links-panel')
                
        @show

        @if (false)
        <br><br><br>
        @endif
        
        <main>
            <div class="container">

                @section('main-content')
                @show

            </div>  
        </main>

        @if (false)
        <br><br><br>
        @endif

        <footer>
            @section('footer-content')
            @show
        </footer>

        @section('css-defered')
        @show

        @section('js-defered')
        @show

        {{-- 
            For now not using CDN, but when doing so will use the minified version...
            not minified version here for fallback..
        --}}
        @if (Functions::testVar($usingCDNs))
            @if (true)
                <script src="https://cdnjs.cloudflare.com/ajax/libs/history.js/1.8/bundled/html4+html5/jquery.history.min.js"></script> 
            @else
                <script src="https://cdnjs.cloudflare.com/ajax/libs/history.js/1.8/bundled/html4+html5/jquery.history.js"></script>
            @endif
        @else
            <script src="{{ asset('lib/history.js/scripts/bundled/html4+html5/jquery.history.js') }}"></script>
        @endif

        {{-- 
            a method for tracking used scripts and including them! 
            (from within LARAVEL!)
        --}}
        {{-- A WISHLIST ITEM!!! --}}
        @if (Functions::testVar($scripts))
            @foreach ($scripts as $script)
                @if (Functions::testVar($usingCDNs) && Functions::testVar($script['cdn-url']))
                    <script src="{{ $script['cdn-url'] }}"></script>
                @elseif (Functions::testVar($usingMix) && Functions::testVar($script['mix-path']))
                    <script src="{{ asset($script['mix-path']) }}"></script>
                @elseif (Functions::testVar($script['local-path']))
                    <script src="{{ asset($script['local-path']) }}"></script>
                @endif
            @endforeach
        @endif

        {{-- from Laravel.. Vue.js is now ENABLED! --}}
        <script src="{{ asset('js/app.js') }}"></script>
        
        {{-- 
            this stuff is ours.. so it should come last.. 
            In the @Extending View - call @Parent last!
            UPDATE:
            No need to worry about the position of the 
            _Blade:_Parent Directive,
            As it is OUTSIDE the 'js-defered' _Blade:_Section!
        --}}
        <script src="{{ asset('js/scripts.js') }}" type="text/javascript"></script>

        {{-- 
            Some views need an extra script tag or more,
             put them in this ('js-extra') _Blade:_Section 
             in the extending view. 
        --}}
        @section('js-extra')
        @show
    </body>
</html>
