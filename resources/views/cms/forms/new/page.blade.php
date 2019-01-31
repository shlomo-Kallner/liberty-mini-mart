@extends('cms.forms.template')

@section('form-content')
    @php
        use \App\Utilities\Functions\Functions;
        $hasOrder2 = Functions::getBladedString($page['hasOrder']??'', '');
        $order2 = Functions::getContent($page['order']??'', old('order', '-1'));
        $orderMin2 = Functions::getContent($page['orderMin']??'', old('orderMin', '-1'));
        $orderMax2 = Functions::getContent($page['orderMax']??'', old('orderMax', '-1'));
    @endphp

    @if (Functions::testVar($hasOrder2) && false)
        <div class="form-group">
                <label for="order">Order:</label>
                <select name="order" id="order">
                    <option value="{{ $order2 === 'new' || $order2 === 'sale'? $order2 : '' }}" selected>
                        {{ $order2 }}
                    </option>
                    <option value="new">New Item Sticker</option>
                    <option value="sale">On Sale Item Sticker</option>
                </select>
            </div>
    @endif
@endsection
