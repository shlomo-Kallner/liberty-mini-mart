

@extends('content.template')

@section('main-content')
    @parent 

    @php
        $testing = true;
        use \App\Utilities\Functions\Functions,
            Darryldecode\Cart\Cart;

    
    @endphp

    <div class="row margin-bottom-40">
        <!-- BEGIN CONTENT -->
            @component('lib.themewagon.cart_page')
            
            @endcomponent
        <!-- END CONTENT -->
    </div>    

    
@endsection