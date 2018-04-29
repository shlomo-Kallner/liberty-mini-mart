
<?php
// this stuff is here as part of my 'scaffolding' 
// { --> being used to selectively 'turn off' parts of the default as 
//       I 'bring online' template components. 
// }
// it may be removed once the scaffold is no longer needed...


$useFixedTopNav = false;
$useRightNavBar = false;
?>

@section('header-navbar')
@if($useFixedTopNav)
<nav class="navbar navbar-inverse navbar-fixed-top">
    @else
    <nav class="navbar">
        @endif
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggled collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                    {{-- 
                        NOTE: adding css class 'mobi-toggler' to the above 
                              button tag does absolutely nothing to hide 
                              the button when not at mobile resolutions!
                    --}}
                    <span class="sr-only">Toggle navigation</span>
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="{{ url('') }}">
    <!--                <img style="width: 50px; height: 50px;" src="{{ asset('images/site/liberty-bell-30065_640.png') }}" alt="liberty-bell site logo">-->
                    {{ $siteName }}
                </a>
            </div>
            <div id="navbar" class="navbar-collapse collapse header-navigation">
                <ul class="nav navbar-nav navbar-left">
                    @foreach($navbar as $nav)
                    <li><a href="{{ url($nav['url']) }}">{{ $nav['name'] }}</a></li>
                    @endforeach
                </ul>
                @if($useRightNavBar)
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ url('user/signin') }}">Sign in</a></li>
                    <li><a href="{{ url('user/signup') }}">Sign up</a></li>
                </ul>
                @endif
            </div>
        </div>
    </nav>
    @endsection
