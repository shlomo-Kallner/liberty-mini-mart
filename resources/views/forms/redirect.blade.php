
@php
    //use Session;
    
@endphp

@extends('content.index')

@section('js-extra')
    @parent

    @php
        $redirectToken = session()->get('redirectToken');
    @endphp

    <script>
        $(function(){
            $('#login-modal .modal-body form .form-group:first-of-type')
            .before('<input type="hidden" name="reTok" value="'+ "{{ $redirectToken }}" +'">');
            $('#login-modal').modal('show');
        });
    </script>
        
@endsection