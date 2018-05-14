<?php ?>

<li>
    <a href="{{ url($url) }}">
        <img src="{{ asset($img) }}" alt="{{ $description }}" width="37" height="34">
    </a>
    <span class="cart-content-count">
        @if(false)
        <i class="fa fa-times"></i>
        {{ $quantity }}
        
        @elseif (true)

        <div class="product-quantity">
            <i class="fa fa-times"></i>
            <input id="product-quantity" type="text" value="{{ $quantity }}" readonly name="product-quantity" class="form-control input-sm">
        </div>
            
        @else
        <div class="btn-group btn-group-justified" role="group">
            <button type="button" class="btn btn-warning">
                <i class="fa fa-minus-square-o"></i>
            </button>
            <span class="label label-default text-center">{{ $quantity }}</span>
            <button type="button" class="btn btn-warning">
                <i class="fa fa-plus-square-o"></i>
            </button>
        </div>
        @endif
    </span>
    <strong>
        <a href="{{ url($url) }}">
            {{ $name }}
        </a>
    </strong>
    <em>
        <i class="fa {{ $currencyIcon }}"></i>
        {{ $priceSum }}
    </em>
    <a href="javascript:void(0);" class="del-goods">
        <i class="fa fa-times-circle"></i>
    </a>
</li>


