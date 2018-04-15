<?php
if (!isset($navbar)) {
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
?>
@include('..inc.nav.default')

