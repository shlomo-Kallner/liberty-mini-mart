

@section('search-modal')
{{-- 
    Inspired by the login-modal (above) 
    from Bootstrapious/Universal-1-0 and 
    Bootstrap v3.X docs.. 
--}}
<!-- *** SEARCH MODAL ***
    _________________________________________________________ -->

<div class="modal fade" id="search-modal" tabindex="-1" role="dialog" aria-labelledby="SearchLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
                <h4 class="modal-title" id="SearchLabel">Search</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                        <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="search" name="search"  data-search="{{ csrf_token() }}" placeholder="Search for...">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" name="submit" id="search-btn">Search!</button>
                                </span>
                        </div><!-- /input-group -->
                </div>
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
                                        <div class="col-md-8">
                                            <p>
                                                
                                                Place a blade escaped description here..
                                                Lorem ipsum dolor, sit amet consectetur adipisicing elit. 
                                                Minima nesciunt, dignissimos ipsa nemo nobis fugit repellat 
                                                quibusdam qui cumque eos iure aspernatur officia assumenda odit!
                                                    
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 pull-left">
                                            <div class="pi-price">$29.00</div>
                                        </div>
                                        <div class="col-md-6 pull-right">
                                            <button type="button" class="btn btn-primary">Add to cart</button>
                                        </div>
                                    </div>
                                </div>
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
