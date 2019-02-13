

@extends('content.template')

@section('main-content')
    @parent 

    @php
        $testing = false;
        $useOldBlade = false;
        use \App\Utilities\Functions\Functions,
            Darryldecode\Cart\Cart;
    
    @endphp

    <div class="row margin-bottom-40">
        @if ($useOldBlade)
            <!-- BEGIN CONTENT -->
                @component('lib.themewagon.cart_page')
                
                @endcomponent
            <!-- END CONTENT -->
        @else
        
            <!-- BEGIN CONTENT -->
            <div class="col-md-12 col-sm-12">
                <div class="goods-page">
                    <div id="goodsData"></div>
                    <a class="btn btn-default pull-left" href="{{ url('store') }}" role="button">Continue shopping <i class="fa fa-shopping-cart"></i></a>
                    
                    <form action="{{url('checkout')}}" method="POST" role="form">
                        <button class="btn btn-primary pull-right" type="submit">
                            Checkout <i class="fa fa-check"></i>
                        </button>
                    </form>
                </div>
            </div>
            <!-- END CONTENT -->
        
        @endif
    </div>    

    
@endsection