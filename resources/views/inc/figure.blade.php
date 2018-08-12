
@php
    use \App\Utilities\Functions\Functions;

    $img2 = Functions::getBladedString($img??'','');
    $alt2 = Functions::getBladedString($alt??'','');
    $cap = Functions::getBladedString($cap??'','');

@endphp

<figure class="thumbnail">
    <img class="img-responsive" src="{{ asset($img2) }}" alt="{{ $alt2 }}">
    @if (Functions::testVar($cap2))
        <figcaption>
            {!! $cap2 !!}
        </figcaption>
    @endif
</figure>