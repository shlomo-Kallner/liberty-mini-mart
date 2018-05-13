<?php
// put your code here
?>

@extends('master_test2')

@include('lib.themewagon.fonts')
@include('lib.bootstrapious.fonts')

@include('lib.themewagon.css') 
@include('lib.bootstrapious.css')


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

@include('inc.copyrights')

@endsection


{{-- BEGIN SECTION:  JS Content From Metronic Shop UI --}}
@include('lib.themewagon.js')
{{-- END SECTION:  JS Content From Metronic Shop UI --}}

@section('old-copyrights')
    
    
<!-- *** COPYRIGHT ***
    _________________________________________________________ -->

<div id="copyright">
    <div class="container">
        <hr>
        <div class="row">
            <div class="col-md-3">
                <a class="powered" href="https://github.com/technext/Metronic-Shop-UI/">
                    <img src="{{ asset('images/site/metronic-logo.png') }}" alt="Powered by Metronic Shop UI">
                </a>
            </div>
            @yield('footer-copyright-link')
        </div>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <a href="http://htmlpurifier.org/">
                    <img
                        src="http://htmlpurifier.org/live/art/powered.png"
                        alt="Powered by HTML Purifier" border="0" />
                </a>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <p class="text-center"> <a href="{{url('')}}">{{ $siteName }}</a> &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>
</div>
<!-- /#copyright -->

<!-- *** COPYRIGHT END *** -->

@endsection

@section('cookie-cutter')



<!-- BEGIN PRE-FOOTER -->
<div class="pre-footer">
    <div class="container">
        <div class="row">
            <!-- BEGIN BOTTOM ABOUT BLOCK -->
            <div class="col-md-3 col-sm-6 pre-footer-col">
                <h2>About us</h2>
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam sit nonummy nibh euismod tincidunt ut laoreet dolore magna aliquarm erat sit volutpat. Nostrud exerci tation ullamcorper suscipit lobortis nisl aliquip  commodo consequat. </p>
                <p>Duis autem vel eum iriure dolor vulputate velit esse molestie at dolore.</p>
            </div>
            <!-- END BOTTOM ABOUT BLOCK -->
            <!-- BEGIN BOTTOM INFO BLOCK -->
            <div class="col-md-3 col-sm-6 pre-footer-col">
                <h2>Information</h2>
                <ul class="list-unstyled">
                    <li><i class="fa fa-angle-right"></i> <a href="javascript:;">Delivery Information</a></li>
                    <li><i class="fa fa-angle-right"></i> <a href="javascript:;">Customer Service</a></li>
                    <li><i class="fa fa-angle-right"></i> <a href="javascript:;">Order Tracking</a></li>
                    <li><i class="fa fa-angle-right"></i> <a href="javascript:;">Shipping &amp; Returns</a></li>
                    <li><i class="fa fa-angle-right"></i> <a href="contacts.html">Contact Us</a></li>
                    <li><i class="fa fa-angle-right"></i> <a href="javascript:;">Careers</a></li>
                    <li><i class="fa fa-angle-right"></i> <a href="javascript:;">Payment Methods</a></li>
                </ul>
            </div>
            <!-- END INFO BLOCK -->

            {{-- Removed the Twitter Block... --}}
            
            <!-- BEGIN BOTTOM CONTACTS -->
            <div class="col-md-3 col-sm-6 pre-footer-col">
                <h2>Our Contacts</h2>
                <address class="margin-bottom-40">
                    35, Lorem Lis Street, Park Ave<br>
                    California, US<br>
                    Phone: 300 323 3456<br>
                    Fax: 300 323 1456<br>
                    Email: <a href="mailto:info@metronic.com">info@metronic.com</a><br>
                    Skype: <a href="skype:metronic">metronic</a>
                </address>
            </div>
            <!-- END BOTTOM CONTACTS -->
        </div>
        <hr>
        <div class="row">
            <!-- BEGIN SOCIAL ICONS -->
            <div class="col-md-6 col-sm-6">
                <ul class="social-icons">
                    <li><a class="rss" data-original-title="rss" href="javascript:;"></a></li>
                    <li><a class="facebook" data-original-title="facebook" href="javascript:;"></a></li>
                    <li><a class="twitter" data-original-title="twitter" href="javascript:;"></a></li>
                    <li><a class="googleplus" data-original-title="googleplus" href="javascript:;"></a></li>
                    <li><a class="linkedin" data-original-title="linkedin" href="javascript:;"></a></li>
                    <li><a class="youtube" data-original-title="youtube" href="javascript:;"></a></li>
                    <li><a class="vimeo" data-original-title="vimeo" href="javascript:;"></a></li>
                    <li><a class="skype" data-original-title="skype" href="javascript:;"></a></li>
                </ul>
            </div>
            <!-- END SOCIAL ICONS -->
            <!-- BEGIN NEWLETTER -->
            <div class="col-md-6 col-sm-6">
                <div class="pre-footer-subscribe-box pull-right">
                    <h2>Newsletter</h2>
                    <form action="#">
                        <div class="input-group">
                            <input type="text" placeholder="youremail@mail.com" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit">Subscribe</button>
                            </span>
                        </div>
                    </form>
                </div> 
            </div>
            <!-- END NEWLETTER -->
        </div>
    </div>
</div>
<!-- END PRE-FOOTER -->

<!-- BEGIN FOOTER -->
<div class="footer">
    <div class="container">
        <div class="row">
            <!-- BEGIN COPYRIGHT -->
            <div class="col-md-4 col-sm-4 padding-top-10">
                2015 © Keenthemes. ALL Rights Reserved. 
            </div>
            <!-- END COPYRIGHT -->
            <!-- BEGIN PAYMENTS -->
            <div class="col-md-4 col-sm-4">
                <ul class="list-unstyled list-inline pull-right">
                    <li>
                        <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/corporate/img/payments/western-union.jpg') }}" alt="We accept Western Union" title="We accept Western Union">
                    </li>
                    <li>
                        <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/corporate/img/payments/american-express.jpg') }}" alt="We accept American Express" title="We accept American Express">
                    </li>
                    <li>
                        <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/corporate/img/payments/MasterCard.jpg') }}" alt="We accept MasterCard" title="We accept MasterCard">
                    </li>
                    <li>
                        <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/corporate/img/payments/PayPal.jpg') }}" alt="We accept PayPal" title="We accept PayPal">
                    </li>
                    <li>
                        <img src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/corporate/img/payments/visa.jpg') }}" alt="We accept Visa" title="We accept Visa">
                    </li>
                </ul>
            </div>
            <!-- END PAYMENTS -->
            <!-- BEGIN POWERED -->
            <div class="col-md-4 col-sm-4 text-right">
                <p class="powered">Powered by: <a href="http://www.keenthemes.com/">KeenThemes.com</a></p>
            </div>
            <!-- END POWERED -->
        </div>
    </div>
</div>
<!-- END FOOTER -->

    
@endsection