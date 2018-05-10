<?php
//$useTimesIcon = true;
/* THE OLD IF USE TIMES ICON OR USE CLOSE ICON FOR MODAL CLOSE..
 * 
 * @if($useTimesIcon)
  <i class="fa fa-times"></i>
  @else
  <i class="fa fa-window-close"></i>
  @endif
 * 
 *  */
?>

@section('login-modal')
{{-- Based on Bootstrapious/Universal-1-0 and Bootstrap v3.X docs.. --}}
<!-- *** LOGIN MODAL ***
    _________________________________________________________ -->

<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
                <h4 class="modal-title" id="Login">Sign In</h4>
            </div>
            <div class="modal-body">
                <form action="{{ url('signin') }}" method="post" novalidate="novalidate">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-at"></i>
                            </span>
                            <input type="email" class="form-control" id="email_modal" placeholder="email" name="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-key"></i>
                            </span>
                            <input type="password" class="form-control" id="password_modal" placeholder="password" name="password">
                        </div>
                    </div>
                    <p class="text-center">
                        <button class="btn btn-template-main"><i class="fa fa-sign-in"></i> Sign In</button>
                    </p>
                </form>

                <p class="text-center text-muted">Not registered yet?</p>
                <p class="text-center text-muted"><a href="{{ url('signup') }}"><strong>Register now</strong></a>! It is easy, can be done in 1&nbsp;minute and gives you access to special discounts and much more!</p>

            </div>
        </div>
    </div>
</div>
<!-- *** LOGIN MODAL END *** -->
@endsection


@section('search-modal')
{{-- 
    Inspired by the login-modal (above) 
    from Bootstrapious/Universal-1-0 and 
    Bootstrap v3.X docs.. 
--}}
<!-- *** SEARCH MODAL ***
    _________________________________________________________ -->

<div class="modal fade" id="search-modal" tabindex="-1" role="dialog" aria-labelledby="Search-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
                <h4 class="modal-title" id="Search-label">Search</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="search" name="search"  data-search="{{ csrf_token() }}" placeholder="Search for...">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" name="submit" id="search-btn">Search!</button>
                    </span>
                </div><!-- /input-group -->

                <div id="search-results-box">

                    @for($idx = 0; $idx < 4; $idx++)
                    
                    {{-- 
                        A Template / Memo Outline for a search result,
                        to be filled in / generated via jQuery/AJAX.
                        May recieve a csrf refresh token as well...
                        This is a Product result.. 
                        To be added: category, page, section, blog post, review, etc..
                    --}}
                    <div class="media">
                            <div class="media-left">
                                <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">
                                    <img class="media-object" width="128" height="128" src="{{ asset('lib/themewagon/metronicShopUI/theme/assets/pages/img/products/model4.jpg') }}" alt="{{ 'Berry Lace Dress' }}" class="img-responsive">
                                </a>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">
                                    <a href="{{ url('lib/themewagon/metronicShopUI/theme/shop-item.html') }}">Berry Lace Dress</a>
                                </h4>
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md8">
                                            <p>
                                                
                                                Place a blade escaped description here..
                                                Lorem ipsum dolor, sit amet consectetur adipisicing elit. 
                                                Minima nesciunt, dignissimos ipsa nemo nobis fugit repellat 
                                                quibusdam qui cumque eos iure aspernatur officia assumenda odit!
                                                    
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="pi-price">$29.00</div>
                                <button type="button" class="btn btn-primary pull-right">Add to cart</button>                                    
                            </div>
                        </div>
                        
                    @endfor
                </div>
            </div>
            <div class="modal-footer center-block" style="text-align: center;">
                <nav aria-label="Page navigation" class="navbar navbar-default navbar-fixed-bottom">
                    <div class="container-fluid">
                        <ul class="pagination">
                            <li>
                                <a href="#" aria-label="Previous">
                                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li>
                                <a href="#" aria-label="Next">
                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- *** SEARCH MODAL END *** -->
@endsection
