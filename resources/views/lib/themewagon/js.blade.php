

@section('js-defered')

  <!-- BEGIN SECTION:  JS link tags From Metronic Shop UI -->
  {{-- 
    was using PHP comments to "hide" dev-comments from end-user... switched to laravel's ..
    jQuery (necessary for Bootstrap's JavaScript plugins).
    Note: using IE compatability comments
    (https://docs.microsoft.com/en-us/previous-versions/windows/internet-explorer/ie-developer/compatibility/ms537512(v=vs.85))
    to detect IE8 and previous for bootstrap 3 support..

  --}}

  <!-- Load javascripts at bottom, this will reduce page load time -->
  
  <!-- BEGIN CORE PLUGINS (REQUIRED FOR ALL PAGES) -->

  @if(false)

    {{-- If we are using CDNs.. --}}

    <!--[if lt IE 9]>
      {{-- If IE is LESSER THAN 9.. --}}
      
      {{-- 
        We are not using our 'inc.js.compatibility' view.. 
        UPDATE: Oh yes we are USING 'inc.js.compatibility',
        see comment in 'master[_test2].blade.php'
      --}}
      @if(false)
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/selectivizr/1.0.2/selectivizr-min.js"></script>
      @endif
      
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <![endif]-->
    <![if gte IE 9]>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <![endif]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/3.0.1/jquery-migrate.min.js"></script>
    <!-- Latest compiled and minified Bootstrap 3 JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <!-- OwlCarousel2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.3/owl.carousel.min.js"></script>


  @else

    <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/respond.min.js') }}"></script>  
    <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/jquery-migrate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>      

    <!-- OwlCarousel2 -->
    <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/owl.carousel/owl.carousel.min.js') }}" type="text/javascript"></script><!-- slider for products -->
    

  @endif

  {{-- Other Metronic Scripts --}}
  
  <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/corporate/scripts/back-to-top.js') }}" type="text/javascript"></script>
  <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
       

  <!-- END CORE PLUGINS -->

  <!-- BEGIN PAGE LEVEL JAVASCRIPTS (REQUIRED ONLY FOR CURRENT PAGE) -->
  <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/fancybox/source/jquery.fancybox.pack.js') }}" type="text/javascript"></script><!-- pop up -->

  <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/zoom/jquery.zoom.min.js') }}" type="text/javascript"></script><!-- product zoom -->
  <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/bootstrap-touchspin/bootstrap.touchspin.js') }}" type="text/javascript"></script><!-- Quantity -->

  <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/corporate/scripts/layout.js') }}" type="text/javascript"></script>
  <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/scripts/bs-carousel.js') }}" type="text/javascript"></script>
  
<!-- END PAGE LEVEL JAVASCRIPTS -->        

<!-- END SECTION:  JS link tags From Metronic Shop UI -->
{{-- 
    From Master-test2.blade.php: 
    In the @extending View - call @Parent last!  
    UPDATE:
    No need to worry about the position of the 
    _Blade:_Parent Directive,
    As 'our-stuff' is OUTSIDE the 'js-defered' _Blade:_Section!
    But nonetheless...
--}}
@parent
@endsection

@section('js-extra')
  @parent
  <script type="text/javascript">
    jQuery(document).ready(function () {
        Layout.init();
        Layout.initOWL();
        Layout.initImageZoom();
        Layout.initTouchspin();
        Layout.initTwitter();

        Layout.initFixHeaderWithPreHeader();
        Layout.initNavScrolling();
    });
  </script>
@endsection
