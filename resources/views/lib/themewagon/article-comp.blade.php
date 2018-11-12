
@php
    use \App\Utilities\Functions\Functions;

    //dd($page);
    //$page2 = Functions::getUnBladedContent($page??'');

    $containerCss2 = Functions::getBladedString($containerCss??'col-md-12','col-md-12');
    $header2 = Functions::getBladedString($header??'');
    $subheading2 = Functions::getBladedString($subheading??'');
    $img2 = Functions::getUnBladedContent($img??[],[]);
    $article2 = Functions::getBladedString($article??'');

    //dd($containerCss2, $header2, $subheading2, $img2, $article2);

@endphp

<div class="{{$containerCss2}}">

    @if (Functions::testVar($header2))
        <h2>
            {!! $header2 !!} 
        </h2>
    @endif
    
    @if (Functions::testVar($img2))
        @php
            //dd($img2);
        @endphp
        @component('inc.figure')
            @foreach ($img2 as $key => $item)
                @slot($key)
                    {!! $item !!}
                @endslot
            @endforeach
        @endcomponent
    @endif

    @if (Functions::testVar($subheading2))
        <h4>
            {!! $subheading2 !!}
        </h4>
    @endif
    
</div>
@if (Functions::testVar($article2))
    <div class="{{$containerCss2}}">
        {!! $article2 !!}
    </div>
@endif


