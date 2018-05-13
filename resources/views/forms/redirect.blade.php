
@php
    use Session;
@endphp

@extends('content.tests.template')

@section('js-defered')
@parent

<script>
    $(function(){
        $('#login-modal .modal-body form .form-group:first-of-type')
        .before('<input type="hidden" name="reTok" value="'+ {{ Session::get('redirectToken') }} +'">');
        $('#login-modal').modal('show');
    });
</script>
    
@endsection