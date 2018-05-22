@php
use \App\Utilities\Functions\getBladedContent,
    \App\Utilities\Functions\testBladedVar,
    \App\Utilities\Functions\testVar;


@endphp

@if (testVar($extraOuterCss))
    <div class="{{$extraOuterCss}}">
@else
    <div>    
@endif

    <div class="product-item">
        <div class="pi-img-wrapper">
            <img src="{{ asset($img) }}" class="img-responsive" alt="{{ $name }}">
            <div>
                <a href="{{ url($img) }}" class="btn btn-default fancybox-button">Zoom</a>
                <a href="#product-pop-up" class="btn btn-default fancybox-fast-view" 
                    data-fancybox="product" data-product-id="{{ $id }}">View</a>
            </div>
        </div>
        <h3><a href="{{ url($url) }}">{{ $name }}</a></h3>
        <div class="pi-price">
            <i class="fa {{ $currency }}"></i>
            <span>{{ $price }}</span>
        </div>
        <a href="javascript:;" class="btn btn-default add2cart" data-product-id="{{ $id }}">Add to cart</a>
        @if(testVar($sticker))
        <div class="sticker {{ $sticker }}"></div>
        @endif
    </div>
</div>