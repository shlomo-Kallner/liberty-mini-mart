<?php ?>

<li>
    <a href="{{ url($url) }}">
        <img src="{{ asset($img) }}" alt="{{ $description }}" width="37" height="34">
    </a>
    <span class="cart-content-count">
        @if(false)
        <i class="fa fa-times" aria-hidden="true"></i>
        <input type="number" value="{{ $quantity }}" 
               min="0" max="100" step="1" class="">
        @else
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-warning">
                <i class="fa fa-minus-square-o"></i>
            </button>
            <div class="well well-sm">{{ $quantity }}</div>
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
        <span>{{ $priceSum }}</span>
    </em>
    <a href="javascript:void(0);" class="del-goods">
        <i class="fa fa-times-circle"></i>
    </a>
</li>


