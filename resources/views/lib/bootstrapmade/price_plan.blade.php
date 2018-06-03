@php
    $testing = false;
    use \App\Utilities\Functions\Functions;

    $name2 = Functions::getBladedString($name??'','');
    $popular2 = Functions::getBladedString($popular??'','');
    $currency2 = Functions::getBladedString($currency??'','fa-usd');
    $price2 = Functions::getBladedString($price??'','');
    $details2 = Functions::getUnBladedContent($details??'','');
    $buttons2 = Functions::getUnBladedContent($buttons??'','');

@endphp



<div class="col-md-3 text-center">
    <div class="panel panel-default panel-pricing panel-pricing-highlighted text-center">
        <div class="panel-heading">
            <h4 class="panel-title">
                {{$name}}
                @if(Functions::testVar($popular) && $popular === 'true')
                <span class="panel-pricing-popular"><i class="fa fa-thumbs-up"></i> Popular</span>
                @endif
            </h4>
        </div>

        @if(Functions::testVar($price2))
            <div class="panel-pricing-price">
                    <i class="fa {{ $currency2 }}"></i>
                @if(!is_numeric($price2))
                    {{-- <i class="fa fa-thumbs-up"></i> FREE!! --}}
                    {{ $price2 }}
                @else 
                    <span class="digits">{{ $price2 }}</span> /mo.
                @endif
            </div>
        @endif
        
        <div class="panel-body">
            <ul class="list-dotted">
                
                @foreach ($details2 as $detail)
                    <li>{{$detail}}</li>
                @endforeach
                
            </ul>
            @foreach ($buttons2 as $button)
            @if (true)
                <a href="#" class="btn btn-primary btn-sm">Choose Plan</a>
            @else
                @component('lib.themewagon.links')
                    
                @endcomponent
            @endif
            <a href="#" class="btn btn-primary btn-sm">Choose Plan</a>
            @endforeach
            <a href="#" class="btn btn-primary btn-sm">Choose Plan</a>

        </div>
    </div>
</div>
