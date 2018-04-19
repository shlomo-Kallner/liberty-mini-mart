

@section('js-defered')

<!-- BEGIN SECTION:  JS Content From Metronic Shop UI -->
{{-- 
 was using PHP comments to "hide" dev-comments form end-user... switched to laravel's ..
 jQuery (necessary for Bootstrap's JavaScript plugins).
  Note: using IE compatability comments
  (https://docs.microsoft.com/en-us/previous-versions/windows/internet-explorer/ie-developer/compatibility/ms537512(v=vs.85))
  to detect IE8 and previous for bootstrap 3 support..

--}}
<!--[if lt IE 9]>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<![endif]-->
<![if gte IE 9]>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<![endif]>

<!-- Latest compiled and minified Bootstrap 3 JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<!-- OwlCarousel2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.3/owl.carousel.min.js"></script>


<!-- END SECTION:  JS Content From Metronic Shop UI -->
@parent
@endsection
