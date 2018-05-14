



<div class="col-md-3 text-center">
    <div class="panel panel-default panel-pricing panel-pricing-highlighted text-center">
        <div class="panel-heading">
            <h4 class="panel-title">
                {{$name}}
                @if(isset($popular) && $popular === 'true')
                <span class="panel-pricing-popular"><i class="fa fa-thumbs-up"></i> Popular</span>
                @endif
            </h4>
        </div>
        <div class="panel-pricing-price">
            @if(!is_numeric($price))
            <i class="fa fa-thumbs-up"></i> FREE!!
            @else
                <i class="fa {{ $currency }}"></i> 
                <span class="digits">{{ $price }}</span> /mo.</div>
            @endif
        <div class="panel-body">
            <ul class="list-dotted">
                
                @foreach (unserialize($details) as $detail)
                    <li>{{$detail}}</li>
                @endforeach
                
            </ul>
            <a href="#" class="btn btn-primary btn-sm">Choose Plan</a>

        </div>
    </div>
</div>
