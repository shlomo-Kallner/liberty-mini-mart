@extends('content.template')

@php
    use \App\Utilities\Functions\Functions,
        \App\Page;

        $order2 = Functions::getContent($page['order']??[],[]);
        $currency2 = Functions::getContent($cart['currencyIcon']??'','fa-usd');

        $comments2 = Functions::getContent($order2['comments']??'', old('comments',''));
        $status2 = Functions::getBladedString($order2['status']??'', old('status',''));

        $thisURL2 = Functions::getBladedString($page['thisURL']??'', request()->path());
        $httpVerb2 = Functions::getBladedString($page['HttpVerb']??'', '');
        $cancelUrl2 = Functions::getBladedString($page['cancelUrl']??'', 'admin');
    
@endphp

@section('main-content')
    @parent

    <div class="row margin-bottom-40 margin-top-40">
        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
        </div>
        
        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">

            @if (Functions::testVar($order2))
                <div class="panel panel-default" id="orderPanel">
                    
                    <div class="panel-heading" id="orderHeading">
                        <h3 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#orderPanel" 
                            href="#orderCollapse" aria-expanded="true" aria-controls="orderCollapse">
                                Order Details:
                            </a>
                        </h3>
                    </div>
                    <div id="orderCollapse" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="orderHeading">
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
                </div>
            @endif
            
            <form action="{{ url($thisURL2) }}" method="POST" role="form" enctype="multipart/form-data" novalidate="novalidate">
                {{ csrf_field() }}
                @if (Functions::testVar($httpVerb2))
                    {{ method_field($httpVerb2) }}
                @endif

                <div class="form-group">
                    <label for="status">Status:</label>
                    <input type="text" class="form-control" id="status" name="status" value="{{ $status2 }}" placeholder="Input Status.." required="required">
                </div>

                <div class="row">
                    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <label for="articleSummernote">Comments</label>
                        <textarea name="comments" id="articleSummernote" cols="50" rows="20"></textarea>
                    </div>
                </div>
            
                <button type="submit" class="btn btn-primary pull-right">Submit</button>
                <input type="reset" value="Reset" class="btn btn-default">
                <a class="btn btn-default pull-left" href="{{ url($cancelUrl2) }}" role="button">Cancel</a>
                
            </form>
            
        </div>
        
        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
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

@section('js-extra')
    @parent
    <script>
        /** this script section is to be written here and then converted into a 
        *   Javascript file of its own and loaded here..
        **/

        jQuery(function($) 
        {
            var esc = '{{ $comments2 }}';
            var unesc = '{!! $comments2 !!}';
            var oldCom = 'editor.insertText';
            var newCom = 'code';
            $('#articleSummernote').summernote(newCom, unesc);
        });
    </script>
@endsection    