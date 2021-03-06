 
<!-- *** COPYRIGHT ***
    _________________________________________________________ -->

<div id="copyright">
    <div class="container">
        <hr>
        <div class="row">

            @include('lib.themewagon.copyright')
            @include('lib.bootstrapious.copyright')
            @include('lib.bootstrapmade.copyright')
            @include('lib.startbootstrap.copyright')

            <div class="col-md-3">
                <p class="powered">
                    Powered by: <br>
                    <a class="powered" href="http://htmlpurifier.org/">
                        <img src="{{ asset('images/site/poweredByHtmlPurifier.png') }}"
                            alt="Powered by HTML Purifier" border="0" />
                    </a> <br>
                    From: <a href="http://htmlpurifier.org/">HTML Purifier</a>
                </p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                @php
                    $sn = $siteName ?? App\Http\Controllers\MainController::$data['site']['name'];
                @endphp
                <p class="text-center"> 
                    <a href="{{url('')}}">{{ $sn }}</a> &copy; {{ date('Y') }}
                </p>
            </div>
        </div>
    </div>
</div>
<!-- /#copyright -->

<!-- *** COPYRIGHT END *** -->
 

