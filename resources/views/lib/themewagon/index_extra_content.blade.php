<?php
// put your code here
?>


@section('css-extra-fonts')
@parent
<script>console.log("Entering extra-fonts-section");</script>

{{-- the font to be placed in a yield or in a child (extending) view.. --}}
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900&amp;subset=all" rel="stylesheet" type="text/css">
<!--- fonts for slider on the index page -->  

<script>console.log("Leaving extra-fonts-section");</script>
@endsection


@section('css-preloaded-local')
{{-- page local css --}}
@parent

<!-- Page level plugin styles START -->
<link href="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/css/animate.css') }}" rel="stylesheet">
<link href="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/fancybox/source/jquery.fancybox.css') }}" rel="stylesheet">
<link href="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/owl.carousel/assets/owl.carousel.css') }}" rel="stylesheet">
<!-- Page level plugin styles END -->

@endsection
