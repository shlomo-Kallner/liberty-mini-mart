@extends('content.base')

@section('main-content')
    @parent
    
    @php
        use \App\Utilities\Functions\Functions;

        $sidebar2 = Functions::getContent($sidebar??[],[]);
        // $article2 = Functions::getContent($page['article']??[],[]);
        $order2 = Functions::getContent($page['order']??[],[]);
        $currency2 = Functions::getContent($cart['currencyIcon']??'','fa-usd');
        $filters2 = Functions::toBladableContent(Functions::getContent($page['filters']??'', ''));
        
        if (!Functions::hasPropKeyIn($page, 'pagination')) {
            $itemsPerPage2 = intval(Functions::getContent($page['itemsPerPage']??'', '12'));
            $pageNumber2 = intval(Functions::getContent($page['pageNumber']??'', '-1'));
            $paginator2 = Functions::toBladableContent($page['pagination']??[], []);
        } else {
            $itemsPerPage2 = intval(Functions::getContent($page['pagination']['numItemsPerPage']??'', '12'));
            $pageNumber2 = intval(Functions::getContent($page['pagination']['currentPage']??'', '-1'));
            $paginator2 = Functions::toBladableContent($page['pagination']??[], []);
        }
        $sorting2 = Functions::toBladableContent(Functions::getContent($page['sorting']??'', ''));
        $type2 = Functions::getBladedString($page['type']??'', '');
       
        //dd($items2);

    @endphp

    <div class="row margin-bottom-40">
                
        @component('lib.themewagon.sidebar')
            @slot('menu')
                {!! Functions::toBladableContent($sidebar2) !!}
            @endslot
            @slot('sidebarClasses')
                {!! 'col-md-3 col-sm-5' !!}
            @endslot
        @endcomponent

        <div class="col-md-9 col-sm-7">

            @if (Functions::testVar($order2))
                <div class="panel panel-default">
                    
                    <div class="panel-heading">
                        <h3 class="panel-title">Status: {{$order2['status']}}</h3>
                    </div>
                    
                    <div class="panel-body">
                    
                        @if (Functions::testVar($order2['content']))
                            @if (Functions::countHas($order2['content']['items']))
                                <div class="table-wrapper-responsive table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="checkout-image">Image</th>
                                                <th class="checkout-description">Description</th>
                                                @if (false)
                                                    <th class="checkout-model">Model</th>
                                                @endif
                                                <th class="checkout-quantity">Quantity</th>
                                                <th class="checkout-price">Price</th>
                                                <th class="checkout-total">Total</th>
                                            </tr>          
                                        </thead>
                                        <tbody>
                                            @foreach ($order2['content']['items'] as $item)
                                                <tr>
                                                    <td class="checkout-image">
                                                        <a href="{{ url($item['img']) }}" class="btn btn-default fancybox.image fancybox-button">
                                                            <img src="{{ asset($item['img']) }}" alt="{{ $item['name'] }}">
                                                        </a>
                                                    </td>
                                                    <td class="checkout-description">
                                                            <!-- Button trigger modal -->
                                                            <button type="button" class="btn btn-link btn-lg" data-toggle="modal" data-target="{{'#myModal-' . $item['id']}}">
                                                                {{ $item['title'] ?? $item['name'] }}
                                                            </button>
                                                            
                                                            <!-- Modal -->
                                                            <div class="modal fade" id="{{'myModal-' . $item['id']}}" tabindex="-1" role="dialog" aria-labelledby="{{'myModalLabel-' . $item['id']}}">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                            <h3 class="modal-title" id="{{'myModalLabel-' . $item['id']}}">
                                                                                <a href="{{ url($item['url']) }}">
                                                                                    {{ $item['name'] }}
                                                                                </a>
                                                                            </h3>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6 col-md-offset-3">
                                                                                    {{ $item['description'] }}
                                                                                </div>
                                                                            </div>
                                                                            @if (Functions::countHas($item['conditions']))
                                                                                <div class="row">
                                                                                    <div class="col-md-6 col-md-offset-3">
                                                                                        <ul>
                                                                                            @foreach ($item['conditions'] as $cond)
                                                                                                <li>                                                        
                                                                                                    <em>{{ $cond['name'] }}</em>
                                                                                                    <strong class="price">
                                                                                                            <i class="fa {{ $currency2 }}"></i>
                                                                                                            {{ $cond['calcValue'] }}
                                                                                                    </strong> 
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                    </td>
                                                    @if (false)
                                                        <td class="checkout-model">RES.193</td>
                                                    @endif
                                                    <td class="checkout-quantity">{{ $item['quantity'] }}</td>
                                                    <td class="checkout-price">
                                                        <strong>
                                                                <i class="fa {{ $currency2 }}"></i>
                                                                {{ $item['priceCalc'] }}
                                                        </strong>
                                                    </td>
                                                    <td class="checkout-total">
                                                        <strong>
                                                                <i class="fa {{ $currency2 }}"></i>
                                                                {{ $item['priceSum']}}
                                                        </strong>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            <div class="checkout-total-block">
                                <ul>
                                    <li>
                                        <em>Sub total</em>
                                        <strong class="price">
                                            <i class="fa {{ $currency2 }}"></i>
                                            {{ $order2['content']['subTotal'] }}
                                        </strong>
                                    </li>
                                    @if (Functions::countHas($order2['content']['conditions']))
                                        @foreach ($order2['content']['conditions'] as $cond)
                                            <li>
                                                <em>{{ $cond['name'] }}</em>
                                                <strong class="price">
                                                        <i class="fa {{ $currency2 }}"></i>
                                                        {{ $cond['calcValue'] }}
                                                </strong>
                                            </li>
                                        @endforeach
                                    @endif
                                    <li class="checkout-total-price">
                                        <em>Total</em>
                                        <strong class="price">
                                            <i class="fa {{ $currency2 }}"></i>
                                            {{ $order2['content']['total'] }}
                                        </strong>
                                    </li>
                                </ul>
                            </div>
                        @else
                            
                            <div class="well well-lg">
                                <h3>We Are Sorry! This Order has no Content!</h3>
                            </div>
                            
                        @endif

                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Comments: </h3>
                    </div>
                    <div class="panel-body">
                    
                        @if (Functions::testVar($order2['comments']))
                            <div class="well">
                                {{$order2['comments']}}
                            </div>
                        @else
                            <div class="well">
                                No Comments yet..
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            
            
        </div>

    </div>

@endsection

@section('js-main')
    @parent
    @php
        // dd(asset('js/admin_blade.js'), asset(mix('js/admin_blade.js')));
    @endphp
    
    <script src="{{ asset(mix('js/admin_blade.js')) }}" type="text/javascript"></script>
@endsection