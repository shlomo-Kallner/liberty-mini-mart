<?php
// put your code here
?>

@extends('master_test2')

@include('lib.themewagon.css') 


@section('body-tags')
class="ecommerce"
@endsection

{{-- 
    UPDATE: Removing the @Include of 'inc.header_content'. 
            Moving fully over to 'lib.themewagon.nav' for 
            Navigational, Header & Footer Content.
--}}
@include('lib.themewagon.nav')

@section('main-content')
@parent
<div class="row">
    <div class="col-md-5">
        <h1>{!! $page['header'] !!} </h1>
        <div>
            {!! $page['article'] !!}
        </div>
        <i class="fa fa-search" style="font-size: 16px;"></i>
    </div>
    <div class="col-md-5">
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Modi, nulla, porro facilis officiis sequi natus eum nemo totam eius deserunt reprehenderit ducimus quia et itaque animi nostrum adipisci accusantium. Quaerat, eos ipsum expedita totam dolorem rem reiciendis voluptatibus quia dolor quam natus id ipsam aliquam fugiat ullam quibusdam unde corporis minima debitis odit laborum numquam repellat illo ea aut mollitia alias? Ut, facere, inventore, mollitia consectetur cum repellat quidem qui itaque modi quam laudantium cupiditate a nemo officia deserunt laboriosam temporibus unde voluptate suscipit labore voluptates cumque quas natus non in maiores dicta delectus omnis aut commodi animi molestiae amet fugit? Tenetur, eligendi, a pariatur laboriosam aliquid cum voluptate nisi laudantium officiis in voluptatum nihil libero consequatur tempora sunt dolorum beatae dicta quod illo impedit!
        </p>
    </div>
</div>

@endsection

@section('footer-content')
@parent
<div class="container">
    <hr>
    <div class="row">
        <div class="col-md-3">
            <a class="powered" href="https://github.com/technext/Metronic-Shop-UI/">
                <img src="{{ asset('images/site/metronic-logo.png') }}" alt="Powered by Metronic Shop UI">
            </a>
        </div>
        <div class="col-md-3">
            <a href="http://htmlpurifier.org/">
                <img
                    src="http://htmlpurifier.org/live/art/powered.png"
                    alt="Powered by HTML Purifier" border="0" />
            </a>
        </div>
    </div>

    <hr>
    <p class="text-center">{{ $siteName }} &copy; {{ date('Y') }}</p>
</div>
@endsection


{{-- BEGIN SECTION:  JS Content From Metronic Shop UI --}}
@include('lib.themewagon.js')
{{-- END SECTION:  JS Content From Metronic Shop UI --}}
