

@extends('content.template')
{{-- 
    a catalog's inner view: 

    Should show the list of sections in the catalog 
    + a side bar with filters & bestsellers..
--}}

@php
$testing = false;

use \App\Utilities\Functions\Functions;

$section2 = serialize(Functions::getContent($page['items']??''));;

$sidebar2 = serialize(Functions::getContent($sidebar??''));
$bestsellers2 = serialize(Functions::getContent($page['bestsellers']??'', ''));
$currency2 = Functions::getContent($cart['currencyIcon']??'','fa-usd');
$filters2 = serialize(Functions::getContent($page['filters']??'', ''));

@endphp

