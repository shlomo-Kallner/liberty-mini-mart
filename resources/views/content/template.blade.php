
@extends('content.base')

@section('footer-content')
    @parent

    @php
        use \App\Page,
        \App\Product,
        \App\Http\Controllers\MainController,
        \App\Utilities\Functions\Functions,
        Illuminate\Support\Facades\Log;

        $siteName2 = Functions::getBladedString($cart['currencyIcon']??'', 'fa-usd');
    @endphp

    @component('lib.themewagon.product_fast_view')
        @slot('currency')
            {!! Functions::toBladableContent($siteName2) !!}
        @endslot
    @endcomponent
@endsection

@section('js-main')
    {{-- from Laravel.. Vue.js is now ENABLED! --}}
    <script src="{{ asset(mix('js/app.js')) }}" type="text/javascript"></script>
@endsection