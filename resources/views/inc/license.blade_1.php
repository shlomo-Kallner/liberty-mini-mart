
@include('lib.themewagon.license')
@yield('license-content')


@verbatim
@extends('master')

<?php
$libFiles = [
    '..lib.themewagon.license'
];
?>
@foreach($libFiles as $libFile)
@include($libFile)
@yield('license-content')
@endforeach
@endverbatim




