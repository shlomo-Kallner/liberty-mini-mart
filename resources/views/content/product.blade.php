
@extends('content.template')


@section('name')
    @parent

    {{-- OUR SLOTS... --}}
    @php
        $testing = true;
        use \App\Utilities\Functions\Functions;

        $sidebarMenu2 = serialize();
        $sidebarProducts2 = serialize();
        $currency2 = '';

    @endphp

    <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
            @component('lib.themewagon.sidebar')
                @slot('menu')
                    {!! $sidebarMenu2 !!}
                @endslot
                {{-- ignoring the 'filters' slot to omit it.. --}}
                @slot('products')
                    {!! $sidebarProducts2 !!}
                @endslot
                @slot('currency')
                    {!! $currency2 !!}
                @endslot
            @endcomponent

            @component('lib.themewagon.product_full_item')
                
            @endcomponent

        </div>
    <!-- END SIDEBAR & CONTENT -->

@endsection

@section('css-preloaded')
    @parent 
    <!-- include summernote css -->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
@endsection

@section('js-defered')
    @parent
    <!-- include summernote js -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
@endsection