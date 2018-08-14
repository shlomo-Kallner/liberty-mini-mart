
@php
    use \App\Utilities\Functions\Functions;

    $img2 = Functions::getBladedString($img??'','');
    $alt2 = Functions::getBladedString($alt??'','');
    $cap2 = Functions::getBladedString($cap??'','');

    //dd($img, $alt, $cap);
    //dd($img2, $alt2, $cap2);

@endphp

@if (Functions::testVar($img2))
    <figure class="thumbnail">
        <img class="img-responsive" src="{{ asset($img2) }}" alt="{{ $alt2 }}">
        @if (Functions::testVar($cap2))
            <figcaption>
                {!! $cap2 !!}
            </figcaption>
        @endif
    </figure>
@endif