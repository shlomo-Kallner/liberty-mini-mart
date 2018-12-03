
@extends('content.base')

@section('footer-content')
    @parent
    @component('lib.themewagon.product_fast_view')
        
    @endcomponent
@endsection

@section('js-main')
    {{-- from Laravel.. Vue.js is now ENABLED! --}}
    <script src="{{ asset(mix('js/app.js')) }}" type="text/javascript"></script>
@endsection