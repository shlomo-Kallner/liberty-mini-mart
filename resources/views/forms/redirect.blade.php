
@extends('content.template')

@section('main-content')
    @parent

    @php
        use \App\Utilities\Functions\Functions;
        $redirectToken = session()->get('redirectToken');
        //dd($redirectToken);
    @endphp
    
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            {{-- leaving this empty for spacing/centering. --}}
        </div>
    </div>
    
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            @component('lib.bootstrapious.modals.login_form')
                @slot('reTok')
                    {{ $redirectToken }}
                @endslot
            @endcomponent
        </div>
    </div>
    
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            {{-- leaving this empty for spacing/centering. --}}
        </div>
    </div>
@endsection
