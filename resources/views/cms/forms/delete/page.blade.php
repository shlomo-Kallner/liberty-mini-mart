@extends('cms.forms.delete.template')

@section('form-content')
    @parent

    @php
        $testing = true;    
        use \App\Utilities\Functions\Functions,
        \App\Page;

        $hasOrder2 = Functions::getBladedString($page['hasOrder']??'', '');
        $order2 = Functions::getContent($page['order']??'', '');
        
    @endphp
    
    @if (Functions::testVar($hasOrder2))
        <h3>Order:</h3>
        <div class="well well-sm">
            <h3>{{ $order2 }}</h3> 
        </div>
    @endif 

@endsection