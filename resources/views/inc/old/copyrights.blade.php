
@section('old-copyrights')
    
    
<!-- *** COPYRIGHT ***
    _________________________________________________________ -->

<div id="copyright">
    <div class="container">
        <hr>
        <div class="row">
            <div class="col-md-3">
                <a class="powered" href="https://github.com/technext/Metronic-Shop-UI/">
                    <img src="{{ asset('images/site/metronic-logo.png') }}" alt="Powered by Metronic Shop UI">
                </a>
            </div>
            @yield('footer-copyright-link')
        </div>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <a href="http://htmlpurifier.org/">
                    <img
                        src="http://htmlpurifier.org/live/art/powered.png"
                        alt="Powered by HTML Purifier" border="0" />
                </a>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <p class="text-center"> <a href="{{url('')}}">{{ $siteName }}</a> &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>
</div>
<!-- /#copyright -->

<!-- *** COPYRIGHT END *** -->

@endsection
