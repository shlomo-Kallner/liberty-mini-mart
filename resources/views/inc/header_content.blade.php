<?php

use \App\Page;

if (!isset($navbar) || empty($navbar)) {
    // some demo code.. 
    // to be replaced with 404 and 503 page 
    // navbar retrieval code
    $navbar = Page::getNavBar();
}
$useDefaultNavbar = false;
/// For testing, dump&die the $navbar variable.
//dd($navbar);
?>
@if($useDefaultNavbar)
@include('inc.nav.default')
@section('header-navbar')
@endsection
@endif
