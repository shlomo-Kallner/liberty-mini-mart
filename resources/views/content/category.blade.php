

@extends('content.template')
{{-- 
    the store inner view: 

    Should show a selection of 'Bestsellers' and the the list of Sections of the store.
--}}

@php

 $sidebar2 = '';
 $filters2 = '';
 $bestsellers2 = '';
 $currency2 = '';

@endphp

@section('main-content')
    @parent

    <div class="row margin-bottom-40 ">

        @component('lib.themewagon.sidebar')
            @slot('sidebar')
                {!! $sidebar2 !!}
            @endslot
            @slot('filters')
                {!! $filters2 !!}
            @endslot
            @slot('bestsellers')
                {!! $bestsellers2 !!}
            @endslot
            @slot('currency')
                {{ $currency2 }}
            @endslot
        @endcomponent

        @component('lib.themewagon.content_list')
            @slot('sorting')
                {{ "" }}
            @endslot
            @slot('products')
                {{ '' }}
            @endslot
            @slot('pageNumber')
                {{ '-1' }}
            @endslot
            @slot('productsPerPage')
                {{ '12' }}
            @endslot
        @endcomponent

    </div>
@endsection


