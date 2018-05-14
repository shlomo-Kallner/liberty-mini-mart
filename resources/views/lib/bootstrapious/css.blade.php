

@section('css-cdn-files')

    @parent
    <!-- CDN UNIVERSAL Fonts START -->          
    <!-- Bootstrap and Font Awesome css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <!-- CDN UNIVERSAL Fonts END -->          

@endsection


@section('css-preloaded-global')
@parent
<!-- Global UNIVERSAL styles START -->          
<!-- Css animations  -->
<link href="{{ asset('lib/bootstrapious/universal/css/animate.css') }}" rel="stylesheet">

<!-- Theme stylesheet, if possible do not edit this stylesheet -->
<link href="{{ asset('lib/bootstrapious/universal/css/style.default.css') }}" rel="stylesheet" id="theme-stylesheet">

<!-- Custom stylesheet - for your changes -->
<link href="{{ asset('lib/bootstrapious/universal/css/custom.css') }}" rel="stylesheet">


<!-- owl carousel css -->

<link href="{{ asset('lib/bootstrapious/universal/css/owl.carousel.css') }}" rel="stylesheet">
<link href="{{ asset('lib/bootstrapious/universal/css/owl.theme.css') }}" rel="stylesheet">

<!-- Global UNIVERSAL styles END -->
@endsection
