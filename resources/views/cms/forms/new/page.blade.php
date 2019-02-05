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
