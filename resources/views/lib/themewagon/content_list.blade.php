
@php
use \App\Utilities\Functions\getBladedContent,
    \App\Utilities\Functions\testBladedVar,
    \App\Utilities\Functions\genRange,
    \App\Utilities\Functions\genPageArray,
    \App\Utilities\Functions\genRowsPerPage,
    \App\Utilities\Functions\genPagesIndexes,
    \App\Utilities\Functions\testVar;

    // The DATA for the SLOTS of THIS COMPONENT are gathered HERE!!!  
    // Note: they CAN be empty... 

    $sorting2 = getBladedContent($sorting);
    $products2 = getBladedContent($products);
    $pageNumber2 = getBladedContent($pageNumber,-1);
    // our default.. is 12 products per page (the template had 9..)
    $productsPerPage2 = getBladedContent($productsPerPage,12);
    $productsPerRow2 = getBladedContent($productsPerRow,3);

    // some calculations based on the Slot_s data..
    $totalProducts = count($products2);

    // Some Utility Functions for the component..

           
@endphp

<!-- BEGIN CONTENT -->
<div class="col-md-9 col-sm-7">
    @if (testVar($sorting2))
        <div class="row list-view-sorting clearfix">
            @if(false)
                {{-- 
                    This merely provides different views of the content, 
                    THIS IS AN ADVANCED_TASK_LIST Item => NOT YET IMPLEMENTED!
                --}}
                
                <div class="col-md-2 col-sm-2 list-view">
                    <a href="javascript:;"><i class="fa fa-th-large"></i></a>
                    <a href="javascript:;"><i class="fa fa-th-list"></i></a>
                </div>
            @endif
            <div class="col-md-10 col-sm-10">
            <div class="pull-right">
                <label class="control-label">Show:</label>
                <select class="form-control input-sm">
                <option value="#?limit=24" selected="selected">24</option>
                <option value="#?limit=25">25</option>
                <option value="#?limit=50">50</option>
                <option value="#?limit=75">75</option>
                <option value="#?limit=100">100</option>
                </select>
            </div>
            <div class="pull-right">
                <label class="control-label">Sort&nbsp;By:</label>
                <select class="form-control input-sm">
                <option value="#?sort=p.sort_order&amp;order=ASC" selected="selected">Default</option>
                <option value="#?sort=pd.name&amp;order=ASC">Name (A - Z)</option>
                <option value="#?sort=pd.name&amp;order=DESC">Name (Z - A)</option>
                <option value="#?sort=p.price&amp;order=ASC">Price (Low &gt; High)</option>
                <option value="#?sort=p.price&amp;order=DESC">Price (High &gt; Low)</option>
                <option value="#?sort=rating&amp;order=DESC">Rating (Highest)</option>
                <option value="#?sort=rating&amp;order=ASC">Rating (Lowest)</option>
                <option value="#?sort=p.model&amp;order=ASC">Model (A - Z)</option>
                <option value="#?sort=p.model&amp;order=DESC">Model (Z - A)</option>
                </select>
            </div>
            </div>
        </div>
        
    @endif

    <!-- BEGIN PRODUCT LIST -->
    @if(testVar($products2))
        
        @php
            if (testVar($productsPerPage2) && $productsPerPage2 <= $totalProducts) {
                // if $productsPerPage is set then 
                //  EVEN IF $pageNumber2 IS NOT set then we have paganation!
                // THOUGH REALLY if we have more products than 
                //  $productsPerRow times $rowsPerPage then we have paganation
                // regardless...
                $rowsPerPage = genRowsPerPage($productsPerPage2 , $productsPerRow2);
            } elseif (testVar($productsPerPage2) && $productsPerPage2 > $totalProducts)) {
                $rowsPerPage = genRowsPerPage($totalProducts , $productsPerRow2);

            } else {
                // our default.. 12 products per page (the template had 9..)
                // though this is very likely to be a dead code branch
                // as the get*() above on $productsPerPage2 was defaulted to 12..
                // So.. this is here for insurance..
                $rowsPerPage = 4;
            }
            $rowsIdxPages = genPageArray(genRange(0,$totalProducts), $productsPerRow2);
            $pagesIdxPages = genPageArray(genRange(0,count($rowsIdxPages)), $rowsPerPage);

            if(testVar($pageNumber2)){
                $idxPages = [];
                $tmp = $pagesIdxPages[$pageNumber2];
                foreach($tmp as $idx){
                    foreach($rowsIdxPages[$idx] as $val){
                        $idxPages[] = $val;
                    }
                }
            } else {
                // by default 
                $idxPages = &$rowsIdxPages;
            }

        @endphp
        {{-- NOTE: every product 'row' can hold up to 3 products. --}}
        @foreach ($idxPages as $page)
            @foreach ($page as $idx)
                <div class="row product-list">
                    @component('lib.themewagon.product_mini')
                        @slot('extraOuterCss')
                            {{ "col-md-4 col-sm-6 col-xs-12" }}
                        @endslot
                        @foreach ($products2[$idx] as $key => $value)
                            @slot($key)
                                {{ $value }}
                            @endslot
                        @endforeach
                    @endcomponent
                </div>
            @endforeach
        @endforeach
        
    @else
        <div class="row product-list">
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="product-item">
                <div class="pi-img-wrapper">
                <img src="assets/pages/img/products/model1.jpg" class="img-responsive" alt="Berry Lace Dress">
                <div>
                    <a href="assets/pages/img/products/model1.jpg" class="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                </div>
                </div>
                <h3><a href="shop-item.html">Berry Lace Dress Berry Lace Dress</a></h3>
                <div class="pi-price">$29.00</div>
                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
            </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="product-item">
                <div class="pi-img-wrapper">
                <img src="assets/pages/img/products/model2.jpg" class="img-responsive" alt="Berry Lace Dress">
                <div>
                    <a href="assets/pages/img/products/model2.jpg" class="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                </div>
                </div>
                <h3><a href="shop-item.html">Berry Lace Dress</a></h3>
                <div class="pi-price">$29.00</div>
                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
            </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="product-item">
                <div class="pi-img-wrapper">
                <img src="assets/pages/img/products/model6.jpg" class="img-responsive" alt="Berry Lace Dress">
                <div>
                    <a href="assets/pages/img/products/model6.jpg" class="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                </div>
                </div>
                <h3><a href="shop-item.html">Berry Lace Dress 2</a></h3>
                <div class="pi-price">$29.00</div>
                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
            </div>
            </div>              
            <!-- PRODUCT ITEM END -->
        </div>
        <div class="row product-list">
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="product-item">
                <div class="pi-img-wrapper">
                <img src="assets/pages/img/products/model4.jpg" class="img-responsive" alt="Berry Lace Dress">
                <div>
                    <a href="assets/pages/img/products/model4.jpg" class="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                </div>
                </div>
                <h3><a href="shop-item.html">Berry Lace Dress Berry Lace Dress</a></h3>
                <div class="pi-price">$29.00</div>
                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
            </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="product-item">
                <div class="pi-img-wrapper">
                <img src="assets/pages/img/products/model5.jpg" class="img-responsive" alt="Berry Lace Dress">
                <div>
                    <a href="assets/pages/img/products/model5.jpg" class="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                </div>
                </div>
                <h3><a href="shop-item.html">Berry Lace Dress</a></h3>
                <div class="pi-price">$29.00</div>
                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                <div class="sticker sticker-new"></div>
            </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="product-item">
                <div class="pi-img-wrapper">
                <img src="assets/pages/img/products/model3.jpg" class="img-responsive" alt="Berry Lace Dress">
                <div>
                    <a href="assets/pages/img/products/model3.jpg" class="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                </div>
                </div>
                <h3><a href="shop-item.html">Berry Lace Dress</a></h3>
                <div class="pi-price">$29.00</div>
                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
            </div>
            </div>              
            <!-- PRODUCT ITEM END -->
        </div>
        <div class="row product-list">
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="product-item">
                <div class="pi-img-wrapper">
                <img src="assets/pages/img/products/model7.jpg" class="img-responsive" alt="Berry Lace Dress">
                <div>
                    <a href="assets/pages/img/products/model7.jpg" class="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                </div>
                </div>
                <h3><a href="shop-item.html">Berry Lace Dress</a></h3>
                <div class="pi-price">$29.00</div>
                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
            </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="product-item">
                <div class="pi-img-wrapper">
                <img src="assets/pages/img/products/model1.jpg" class="img-responsive" alt="Berry Lace Dress">
                <div>
                    <a href="assets/pages/img/products/model1.jpg" class="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                </div>
                </div>
                <h3><a href="shop-item.html">Berry Lace Dress</a></h3>
                <div class="pi-price">$29.00</div>
                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
            </div>
            </div>
            <!-- PRODUCT ITEM END -->
            <!-- PRODUCT ITEM START -->
            <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="product-item">
                <div class="pi-img-wrapper">
                <img src="assets/pages/img/products/model2.jpg" class="img-responsive" alt="Berry Lace Dress">
                <div>
                    <a href="assets/pages/img/products/model2.jpg" class="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                </div>
                </div>
                <h3><a href="shop-item.html">Berry Lace Dress</a></h3>
                <div class="pi-price">$29.00</div>
                <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                <div class="sticker sticker-sale"></div>
            </div>
            </div>
            <!-- PRODUCT ITEM END -->
        </div>
    @endif
    <!-- END PRODUCT LIST -->
    @component('lib.themewagon.paginator')
        
    @endcomponent
</div>
<!-- END CONTENT -->