 
<!-- *** COPYRIGHT ***
    _________________________________________________________ -->

<div id="copyright">
    <div class="container">
        <hr>
        <div class="row">

            @include('lib.themewagon.copyright')
            @include('lib.bootstrapious.copyright')
            @include('lib.bootstrapmade.copyright')

            <div class="col-md-3">
                <p class="powered">
                    Powered by: &nbsp;
                    <a class="powered" href="http://htmlpurifier.org/">
                        <img src="http://htmlpurifier.org/live/art/powered.png"
                            alt="Powered by HTML Purifier" border="0" />
                    </a>
                </p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <p class="text-center"> <a href="{{url('')}}">{{ $siteName }}</a> &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>
</div>
<!-- /#copyright -->

<!-- *** COPYRIGHT END *** -->
 

