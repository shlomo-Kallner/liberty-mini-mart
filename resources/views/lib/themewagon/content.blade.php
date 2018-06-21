

@php
    use \App\Utilities\Functions\Functions;


    $containerCss2 = Functions::getBladedString($containerCss??'');
    $pageHeader2 = Functions::getBladedString($pageHeader??'');
    $articleHeader2 = Functions::getBladedString($articleHeader??'');
    $subheading2 = Functions::getBladedString($subheading??'');
    $img2 = Functions::getBladedString($img??'');
    $imgAlt2 = Functions::getBladedString($imgAlt??'');
    $article2 = Functions::getBladedString($article??'');

@endphp


    @if (Functions::testVar($containerCss2))
        <div class="{{ $containerCss2 }}">
        
    @endif

        @if (Functions::testVar($pageHeader2))
            <h1>
                {!! $pageHeader2 !!}
            </h1>
        @endif

        @if (Functions::testVar($articleHeader2))
            <h2>
                {!! $articleHeader2 !!} 
            </h2>
        @endif

        @if (Functions::testVar($subheading2))
            <h3>
                {!! $subheading2 !!}
            </h3>
        @endif


        @if (Functions::testVar($img2))
            <img src="{{ asset($img2) }}" alt="{{ $imgAlt2 }}">
        @endif

        @if (Functions::testVar($article2))
            {!! $article2 !!}
        @endif
                    

    @if (Functions::testVar($containerCss2))
        </div>
    
    @endif