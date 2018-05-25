
@php
    use \App\Utilities\Functions\Functions;

    //dd($page);
    //$page2 = Functions::getUnBladedContent($page??'');

    $header2 = Functions::getBladedString($header??'');
    $subheading2 = Functions::getBladedString($subheading??'');
    $img2 = Functions::getBladedString($img??'');
    $imgAlt2 = Functions::getBladedString($imgAlt??'');
    $article2 = Functions::getBladedString($article??'');

@endphp

<div class="row">
    <div class="col-md-5">

        @if (Functions::testVar($header2))
            <h1>
                {!! $header2 !!} 
            </h1>
        @endif
        

        @if (Functions::testVar($subheading2))
            <h2>
                {!! $subheading2 !!}
            </h2>
        @endif
        
        
        @if (Functions::testVar($img2))
            <img src="{{ $img2 }}" alt="{{ $imgAlt2 }}">
        @endif
        
        {{-- <i class="fa fa-search" style="font-size: 16px;"></i> --}}
        
    </div>
    @if (Functions::testVar($article2))
        <div class="col-md-5">
            <div>
                {!! $article2 !!}
            </div>
        </div>
    @endif
    
</div>

