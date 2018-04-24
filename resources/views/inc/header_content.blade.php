<?php
if (!isset($navbar) || empty($navbar)) {
    // some demo code.. 
    // to be replaced with 404 and 503 page 
    // navbar retrieval code
    $navbar = [
        [
            'url' => 'about',
            'name' => 'About',
        ],
        [
            'url' => 'store',
            'name' => 'Store',
        ],
    ];
}
$useDefaultNavbar = true;
?>
@if($useDefaultNavbar)
@include('inc.nav.default')
@else
@section('header-navbar')
<script> console.log('in else!');</script>
@endsection
@endif
