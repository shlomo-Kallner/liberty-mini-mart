@php
    use \App\Utilities\Functions\Functions;

    $extraOuterCss2 = Functions::getBladedString($extraOuterCss??'','');
    $img2 = Functions::getBladedString($img??'','');
    $name2 = Functions::getBladedString($name??'','');
    $id2 = Functions::getBladedString($id??'','');
    $url2 = Functions::getBladedString($url??'','');
    $currency2 = Functions::getBladedString($currency??'fa-usd','fa-usd');
    $price2 = Functions::getBladedString($price??'','');
    $sticker2 = Functions::getBladedString($sticker??'','');

@endphp

<div 

@if (Functions::testVar($extraOuterCss2))
    class="{{$extraOuterCss2}}"
@endif
>
    <div class="product-item">
        <div class="pi-img-wrapper">
            <img src="{{ asset($img2) }}" class="img-responsive" alt="{{ $name2 }}">
            <div>
                <a href="{{ url($img2) }}" class="btn btn-default fancybox.image fancybox-button">Zoom</a>
                <a href="#product-pop-up" class="btn btn-default fancybox-fast-view" 
                    data-fancybox="product" data-product-id="{{ $id2 }}">View</a>
            </div>
        </div>
        <h3><a href="{{ url($url2) }}">{{ $name2 }}</a></h3>
        <div class="pi-price">
            <i class="fa {{ $currency2 }}"></i>
            <span>{{ $price2 }}</span>
        </div>
        <a href="javascript:;" class="btn btn-default add2cart" data-product-id="{{ $id2 }}">Add to cart</a>
        <a href="javascript:;" class="btn btn-default pull-right orderNow" data-product-id="{{ $id2 }}">Order Now!</a>
        @if(Functions::testVar($sticker2))
            <div class="sticker {{ $sticker2 }}"></div>
        @endif
    </div>
</div>