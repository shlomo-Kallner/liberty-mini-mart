

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

    @if (false)
        
    @else

    <section class="bar background-white no-mb">
        <div class="container" data-animate="fadeInUp">
            <div class="row">
                <div class="col-md-12">
                    @component('lib.bootstrapious.feature_single_showcase_item')
                    
                    @endcomponent
                </div>
            </div>
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
        
                @if (false)
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
                @endif

                @if (true)
                    @component('lib.bootstrapious.feature_multiple_items')
                        
                    @endcomponent
                @endif

        

            </div>
        </div>
    </section>
        
    @endif
@endsection

@section('js-extra')
    @parent

    
    
@endsection