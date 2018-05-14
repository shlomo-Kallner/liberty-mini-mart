<?php
// put your code here
?>

@extends('master_test2')

@include('lib.themewagon.fonts')
@include('lib.bootstrapious.fonts')

@include('lib.themewagon.css') 
@include('lib.bootstrapious.css')
@include('lib.bootstrapmade.css')


@section('body-tags')
class="ecommerce"
@endsection

{{-- 
    UPDATE: Removing the @Include of 'inc.header_content'. 
            Moving fully over to 'lib.themewagon.nav' for 
            Navigational, Header & Footer Content.
--}}
@include('lib.themewagon.nav')

@include('lib.bootstrapious.modals.login')
@include('lib.bootstrapious.modals.search')

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
@include('lib.themewagon.prefooter')
@include('inc.copyrights')

@endsection


{{-- BEGIN SECTION:  JS Content From Metronic Shop UI --}}
@include('lib.themewagon.js')
{{-- END SECTION:  JS Content From Metronic Shop UI --}}

@section('cookie-cutter')



    
@endsection