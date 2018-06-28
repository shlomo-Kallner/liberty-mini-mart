{{--  
     Compatability Zone... 
     this file exists PRIMARILY for   
     @PARTIAL@ IE 8- support...
     (or for other browsers that don't support HTML5..)
     
     HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries.
WARNING: Respond.js doesn't work if you view the page via file:// .
--}}

@php
  use \App\Utilities\Functions\Functions;
  $usingCDNs2 = Functions::getBladedString($site['usingCDNs']??'','');
@endphp

<!--[if lt IE 9]>
  @if (!Functions::testVar($usingCDNs2))
    <script src="{{ asset('js/compatibility.js') }}"></script>  
  @else
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectivizr/1.0.2/selectivizr-min.js"></script>
  @endif
<![endif]-->

