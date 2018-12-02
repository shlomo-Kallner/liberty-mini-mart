
@php
    use \App\Utilities\Functions\Functions;
    
    $scripts2 = Functions::getContent($scripts??'');
    $usingCDNs2 = Functions::getBladedString($usingCDNs??'');
    $usingMix2 = Functions::getBladedString($usingMix??'');
    $usingMinified2 = Functions::getBladedString($usingMinified??'');
    
@endphp


    {{-- 
        For now not using CDN, but when doing so will use the minified version...
        not minified version here for fallback..
    --}}
    @if (Functions::testVar($usingCDNs2) && false)
        @if (Functions::testVar($usingMinified2))
            <script src="https://cdnjs.cloudflare.com/ajax/libs/history.js/1.8/bundled/html4+html5/jquery.history.min.js"></script> 
        @else
            <script src="https://cdnjs.cloudflare.com/ajax/libs/history.js/1.8/bundled/html4+html5/jquery.history.js"></script>
        @endif
    @elseif (false)
        <script src="{{ mix('lib/history.js/scripts/bundled/html4+html5/jquery.history.js') }}"></script>
    @endif

    {{-- 
        a method for tracking used scripts and including them! 
        (from within LARAVEL!)
    --}}
    {{-- A WISHLIST ITEM!!! --}}
    @if (Functions::testVar($scripts2))
        @foreach ($scripts2 as $script)
            @if (Functions::testVar($usingCDNs2))
                @if (Functions::testVar($usingMinified2) && Functions::testVar($script['cdn-url-min']))
                    <script src="{{ $script['cdn-url-min'] }}"></script>
                @elseif (Functions::testVar($script['cdn-url']))
                    <script src="{{ $script['cdn-url'] }}"></script>
                @endif
            @elseif (Functions::testVar($usingMix2) && Functions::testVar($script['mix-path']))
                <script src="{{ mix($script['mix-path']) }}"></script>
            @elseif (Functions::testVar($script['local-path']))
                <script src="{{ mix($script['local-path']) }}"></script>
            @endif
        @endforeach
    @endif