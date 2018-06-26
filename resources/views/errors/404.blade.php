

@extends('content.template')

@section('pageTitle')
    Ooops! Error 404..
@endsection

@section('main-content')
    
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <h1>Ooops! Error 404..</h1>
                    <h2>We&apos;re Sorry! The Requested Page Could Not Be Found...</h2>
                </div>
                <div class="col-md-2"></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <img src="{{ asset('lib/freepik/images/225203-P29OZD-286.jpg') }}" alt="freepik.com hand drawn 404 error">
                    <a href="https://www.freepik.com/free-vector/hand-drawn-404-error-template_1631738.htm">
                        Designed by Freepik
                    </a>
                </div>
            </div>
        </div>

@endsection