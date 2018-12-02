
@section('js-defered')

  @php
      use App\Utilities\Functions\Functions;

      $usingCDNs = Functions::getBladedString($site['usingCDNs']??'');
  @endphp  


  <!-- Load javascripts at bottom, this will reduce page load time -->
  <!-- BEGIN SECTION:  JS link tags From Metronic Shop UI -->
      {{-- 
        was using PHP comments to "hide" dev-comments from end-user... switched to laravel's ..
        jQuery (necessary for Bootstrap's JavaScript plugins).
        Note: using IE compatability comments
        (https://docs.microsoft.com/en-us/previous-versions/windows/internet-explorer/ie-developer/compatibility/ms537512(v=vs.85))
        to detect IE8 and previous for bootstrap 3 support..

      --}}

    
    <!-- BEGIN CORE PLUGINS (REQUIRED FOR ALL PAGES) -->

      @if(Functions::testVar($usingCDNs))

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
        <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/owl.carousel/owl.carousel.js') }}" type="text/javascript"></script><!-- slider for products -->

      @endif

      {{-- Other Metronic Scripts --}}
      <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/corporate/scripts/back-to-top.js') }}" type="text/javascript"></script>
      @if (false)
        <script>
          function checkSlimscroll($) {
            if ($ === undefined) {
              alert('no Jquery');
            } else {
              if ($.slimscroll === undefined) {
                alert('no $.slimscroll');
              } else {
                alert('we have $.slimscroll');
              }
              if ($.slimScroll === undefined) {
                alert('no $.slimScroll');
              } else {
                alert('we have $.slimScroll');
              }
              if ($.fn.slimScroll === undefined) {
                alert('no $.fn.slimScroll');
              } else {
                alert('we have $.fn.slimScroll');
              }
              if ($.fn.slimScroll === undefined) {
                alert('no $.fn.slimScroll');
              } else {
                alert('we have $.fn.slimScroll');
              }
            } 
          }
          //checkSlimscroll(jQuery);
        </script>
      @endif
      <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/jquery-slimscroll/jquery.slimscroll.js') }}" type="text/javascript"></script>

      @if (false)
        <script>checkSlimscroll(jQuery);</script>
      @endif
      {{--  --}}
      
      
    <!-- END CORE PLUGINS -->

    <!-- BEGIN PAGE LEVEL JAVASCRIPTS (REQUIRED ONLY FOR CURRENT PAGE) -->
      @if (Functions::testVar($usingCDNs))

        <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.3/jquery.fancybox.pack.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-zoom/1.7.21/jquery.zoom.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-touchspin/3.0.1/jquery.bootstrap-touchspin.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Uniform.js/2.1.3/jquery.uniform.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.rateit/1.0.25/jquery.rateit.min.js"></script>
        <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

      @else

        <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/fancybox/source/jquery.fancybox.pack.js') }}" type="text/javascript"></script> {{--  <!-- pop up -->  --}}
        <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/zoom/jquery.zoom.min.js') }}" type="text/javascript"></script> {{-- <!-- product zoom --> --}}
          
        <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/bootstrap-touchspin/bootstrap.touchspin.js') }}" type="text/javascript"></script> {{-- <!-- Quantity --> --}}
        
        <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/rateit/src/jquery.rateit.js') }}" type="text/javascript"></script>
        
        <script src="{{ asset('lib/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js') }}"></script> {{-- <!-- for slider-range --> --}}
        
      @endif

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

@if (false)
  @section('js-extra')
    @parent
    
    @if (false)
      {{-- 
        from Metronic file "shop-index-header-fix.html" 
        with "Layout.initTwitter();" removed..
        added "Layout.initUniform();" and
          "Layout.initSliderRange();" from 
          "metronic/shop-item.html"..
      --}}
      
      <script type="text/javascript">
        jQuery(function ($) {
            Layout.init();
            Layout.initOWL();
            Layout.initImageZoom();
            Layout.initTouchspin();
            Layout.initFixHeaderWithPreHeader();
            Layout.initNavScrolling();
            Layout.initUniform();
            Layout.initSliderRange();
            $.scrolltotop.init2( "{{ url('lib/themewagon/metronicShopUI/theme/assets/corporate/img/up.png') }}" );
        });
      </script>
    @endif

    @if (false)
      <script>
        jQuery(function($){
          alert('DOM loaded!');
          checkSlimscroll($);
        });
      </script>
    @endif

  @endsection
@endif
