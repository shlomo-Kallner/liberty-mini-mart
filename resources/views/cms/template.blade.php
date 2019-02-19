@extends('content.base')

@section('main-content')
    @parent
    
@endsection

@section('js-main')
    @parent
    @php
        // dd(asset('js/admin_blade.js'), asset(mix('js/admin_blade.js')));
    @endphp
    
    <script src="{{ asset(mix('js/admin_blade.js')) }}" type="text/javascript"></script>
@endsection