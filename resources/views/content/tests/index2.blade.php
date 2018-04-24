<?php
// Will be placing our "index" or "Home" page content in this view/file.
?>

@extends('content.tests.template')

@section('css-extra-fonts')
@parent

{{-- the font to be placed in a yield or in a child (extending) view.. --}}
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900&amp;subset=all" rel="stylesheet" type="text/css">
<!--- fonts for slider on the index page -->  

@endsection


@section('css-preloaded-local')
{{-- page local css --}}
@parent

<!-- Page level plugin styles START -->
<link href="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/css/animate.css') }}" rel="stylesheet">
<link href="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/fancybox/source/jquery.fancybox.css') }}" rel="stylesheet">
<link href="{{ asset('lib/themewagon/metronicShopUI/theme/assets/plugins/owl.carousel/assets/owl.carousel.css') }}" rel="stylesheet">
<!-- Page level plugin styles END -->

@endsection


@section('main-content')
@parent
<div class="row">
    <div class="col-md-5">
        <h1>{!! $page['header'] !!} </h1>
        <div>
            {!! $page['article'] !!}
        </div>
    </div>
    <div class="col-md-5">
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Modi, nulla, porro facilis officiis sequi natus eum nemo totam eius deserunt reprehenderit ducimus quia et itaque animi nostrum adipisci accusantium. Quaerat, eos ipsum expedita totam dolorem rem reiciendis voluptatibus quia dolor quam natus id ipsam aliquam fugiat ullam quibusdam unde corporis minima debitis odit laborum numquam repellat illo ea aut mollitia alias? Ut, facere, inventore, mollitia consectetur cum repellat quidem qui itaque modi quam laudantium cupiditate a nemo officia deserunt laboriosam temporibus unde voluptate suscipit labore voluptates cumque quas natus non in maiores dicta delectus omnis aut commodi animi molestiae amet fugit? Tenetur, eligendi, a pariatur laboriosam aliquid cum voluptate nisi laudantium officiis in voluptatum nihil libero consequatur tempora sunt dolorum beatae dicta quod illo impedit!
        </p>
    </div>
</div>
@endsection



