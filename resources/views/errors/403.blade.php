

@extends('content.template')

@section('pageTitle')
    Warning! Error 403..
@endsection

@section('main-content')

        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <h1>Warning! Error 403..</h1>
                    <h2>We&apos;re Sorry! Access Denied...</h2>
                </div>
                <div class="col-md-2"></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <img src="{{ asset('lib/freepik/images/OE5NS20.jpg') }}" 
                         alt="freepik.com character next to a padlock with key">
                    <a href="https://www.freepik.com/free-photo/character-next-to-a-padlock-with-key_954674.htm">
                        Designed by Freepik
                    </a>
                </div>
            </div>
        </div>
        
@endsection


