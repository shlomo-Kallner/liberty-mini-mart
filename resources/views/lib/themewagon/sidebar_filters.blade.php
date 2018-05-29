


@php

    $testing = false;
    $fakeData = ''; // old fakeData '123FAKEDATA'..
    use \App\Utilities\Functions\Functions,
        \App\Utilities\IterationStack\IterationStack,
        \App\Utilities\IterationStack\IterationFrame;

    //dd($testing);
    $filters2 = Functions::getUnBladedContent($filters??$fakeData,$fakeData);
    //dd($filters);
    //dd($filters2);
    $title2 = Functions::getBladedString($title??$fakeData,$fakeData);
    $currency2 = Functions::getBladedString($currency??'fa-usd');
    //dd($currency2);

@endphp


@if(Functions::testVar($filters2) || $testing)
    <div class="sidebar-filter margin-bottom-25">
        <h2>{{ $title2 }}</h2>

        @if (Functions::testVar($filters2))
            @php
                //dd($filters2);
            @endphp

            @foreach ($filters2 as $item)

                <h3>{{ $item['name'] }}</h3>
                {!! Functions::getBladedString($item['filter']) !!}
                
            @endforeach

        @elseif($testing)

            <h3>Availability</h3>
            <div class="checkbox-list">
                <label><input type="checkbox"> Not Available (3)</label>
                <label><input type="checkbox"> In Stock (26)</label>
            </div>

            <h3>Price</h3>
            <p>
                <label for="amount">Range:</label>
                <input type="text" id="amount" style="border:0; color:#f6931f; font-weight:bold;">
            </p>
            <div id="slider-range"></div>
            
        @endif
        
    </div>
@endif
