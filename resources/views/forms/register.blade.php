
@extends('content.template')

@section('main-content')
    @parent

    @php
    $testing = true;
    use \App\Utilities\Functions\Functions;

    
    @endphp

    @component('lib.bootstrapmade.pricings_list')
        
    @endcomponent
@endsection