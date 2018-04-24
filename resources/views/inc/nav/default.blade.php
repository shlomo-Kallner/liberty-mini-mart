@section('header-navbar')
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggled collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('') }}">
<!--                <img style="width: 50px; height: 50px;" src="{{ asset('images/site/liberty-bell-30065_640.png') }}" alt="liberty-bell site logo">-->
                {{ $siteName }}
            </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                @foreach($navbar as $nav)
                <li><a href="{{ url($nav['url']) }}">{{ $nav['name'] }}</a></li>
                @endforeach
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ url('user/signin') }}">Sign in</a></li>
                <li><a href="{{ url('user/signup') }}">Sign up</a></li>
            </ul>
        </div>
    </div>
</nav>
@endsection
