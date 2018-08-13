
@php
    use \App\Utilities\Functions\Functions;

    //dd($page);
    //$page2 = Functions::getUnBladedContent($page??'');

    $containerCss2 = Functions::getBladedString($containerCss??'col-md-12','col-md-12');
    $header2 = Functions::getBladedString($header??'');
    $subheading2 = Functions::getBladedString($subheading??'');
    $img2 = Functions::getUnBladedContent($img??[],[]);
    $article2 = Functions::getBladedString($article??'');

@endphp

<div class="row">
    <div class="{{$containerCss2}}">

        @if (Functions::testVar($header2))
            <h2>
                {!! $header2 !!} 
            </h2>
        @endif
        
        @if (Functions::testVar($img2))
            @component('inc.figure')
                @foreach ($img as $key => $item)
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
</div>
@if (Functions::testVar($article2))
    <div class="row">
        <div class="{{$containerCss2}}">
                {!! $article2 !!}
        </div>
    </div>
@endif


