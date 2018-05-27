

@section('js-defered')
@parent
<!-- BEGIN SECTION:  JS link tags From UNIVERSAL 1.0 -->

    <script>
        window.jQuery || document.write('<script src="lib/bootstrapious/universal/js/jquery-1.11.0.min.js"><\/script>')
    </script>
    
    <script src="{{ asset('lib/bootstrapious/universal/js/jquery.cookie.js') }}"></script>
    <script src="{{ asset('lib/bootstrapious/universal/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/bootstrapious/universal/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('lib/bootstrapious/universal/js/jquery.parallax-1.1.3.js') }}"></script>
    <script src="{{ asset('lib/bootstrapious/universal/js/front.js') }}"></script>

    
<!-- END SECTION:  JS link tags From UNIVERSAL 1.0 -->
@endsection

@section('js-extra')
@parent
    
@endsection

