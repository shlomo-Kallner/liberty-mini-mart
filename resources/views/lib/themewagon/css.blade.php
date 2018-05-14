

{{-- BEGIN SECTION:  CSS Content From Metronic Shop UI --}}
@section('css-preloaded-global')

@parent

<!-- Global styles START -->          
<link href="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
<link href="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<!-- Global styles END -->

@endsection

@section('css-preloaded-local')
{{-- page local css --}}
@parent

@endsection

@section('css-themes')


<!-- Theme styles START -->
<link href="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/css/components.css') }}" rel="stylesheet">
<link href="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/css/slider.css') }}" rel="stylesheet">
<link href="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/css/style-shop.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('lib/themewagon/metronicShopUI/theme/assets/corporate/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('lib/themewagon/metronicShopUI/theme/assets/corporate/css/style-responsive.css') }}" rel="stylesheet">
<link href="{{ asset('lib/themewagon/metronicShopUI/theme/assets/corporate/css/themes/red.css') }}" rel="stylesheet" id="style-color">
<link href="{{ asset('lib/themewagon/metronicShopUI/theme/assets/corporate/css/custom.css') }}" rel="stylesheet">
<!-- Theme styles END -->

@endsection




@section('css-cdn-files')
@parent
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- OwlCarousel2/2.3.3 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.3/assets/owl.carousel.min.css" integrity="sha256-gT8TmL8VMbIMNlQU2BDnXyroZ6cDkXlMoo61fhgRfGY=" crossorigin="anonymous" />
@endsection

{{-- END SECTION:  CSS Content From Metronic Shop UI --}}
