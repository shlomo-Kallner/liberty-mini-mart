@extends('cms.forms.template')

@section('form-content')
    @php
        use \App\Utilities\Functions\Functions;
        $hasOrder2 = Functions::getBladedString($page['hasOrder']??'', '');
        $order2 = Functions::getContent($page['order']??'', old('order', '-1'));
        $orderMin2 = intval(Functions::getContent($page['orderMin']??'', old('orderMin', '1')));
        $orderMax2 = intval(Functions::getContent($page['orderMax']??'', old('orderMax', '1')));
    @endphp

    @if (Functions::testVar($hasOrder2))
        <div class="form-group">
            <label for="order">Order:</label>
            <select name="order" id="order">
                <option value="" {{ $order2 === '' ? 'selected' : '' }}>
                        Please Choose an Ordering Index.
                </option>
                <option value="-1"{{ $order2 === '-1' ? 'selected' : '' }}>
                    Random Ordering Index.
                </option>
                @for ($x = $orderMin2; $x <= $orderMax2; $x++)
                    <option value="{{ $x }}" {{ intval($order2) === $x ? 'selected' : '' }}>
                        {{ $x }}
                    </option>
                @endfor
            </select>
        </div>
    @endif
@endsection



@section('js-extra')
    @parent
    <script>
        /** this script section is to be written here and then converted into a 
        *   Javascript file of its own and loaded here..
        **/

        jQuery(
            function($) 
            {
                var lurl = window.Laravel.baseUrl + '/api/menus/';
                var order = $('.form-group > select#order');
                var menu = $('.form-group > select#menu');
                menu.change(function () {
                    var nurl = lurl + $(this).val() + '/order';
                    var data = window.Laravel.handleCart.makeData(
                        {}, nurl, window.Laravel.csrfToken, '', 'orderList', 
                        window.Laravel.nut
                    );
                    var debug = true;
                    var _this = $(this);
                    window.Laravel.handleCart.doAjax($, data, 'GET', function (res) {
                        order.empty();
                        $('<option value="">Pick a Ordering</option>').appendTo(order);
                        $('<option value="-1">Append to the End Ordering Index.</option>').appendTo(order);
                        for (var i of res) {
                            var opt = $('<option value="' + i + '">' + i + '</option>')
                            .appendTo(order);
                        }
                        alert('Loading of Orderings is Complete. Please Pick a Ordering.');
                    }, debug)
                });
            }
        );
    </script>
@endsection

