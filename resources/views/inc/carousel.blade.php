
@php
    use App\Utilities\Functions\Functions;

    $images2 = Functions::getUnBladedContent($images??[],[]);

    $carouselID2 = 'carousel-' . Functions::getBladedString($carouselID??'','example-generic');;

    $panelCss2 = Functions::getBladedString($panelCss??'','panel-default');
    $panelTitle2 = Functions::getBladedString($panelTitle??'','');
    $panelFooter2 = Functions::getBladedString($panelFooter??'','');
    $withPanel = Functions::testVar($panelCss2) 
        && (Functions::testVar($panelTitle2) || Functions::testVar($panelFooter2));

    //dd($images2, $carouselID2, $withPanel, $panelCss2, $panelTitle2, $panelFooter2);
@endphp


@if (Functions::testVar($images2))
    
    @if ($withPanel)
      
        <div class="panel {{$panelCss2}}">
            @if (Functions::testVar($panelTitle2))
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {!! $panelTitle2 !!}
                    </h3>
                </div>    
            @endif
            
            <div class="panel-body">

    @endif

        <div id="{{$carouselID2}}" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                @foreach ($images2 as $item)
                    <li data-target="{{'#' . $carouselID2}}" 
                        data-slide-to="{{$loop->index}}" 
                        @if ($loop->first)
                            class="active"
                        @endif
                        >
                    </li>
                @endforeach
            </ol>
          
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                @foreach ($images2 as $item)
                    
                    <div  
                        @if ($loop->first)
                            class="item active"
                        @else
                            class="item"
                        @endif
                        
                        >
                        <img src="{{$item['img']}}" alt="{{$item['alt']}}">
                        <div class="carousel-caption">
                            {!! $item['cap'] !!}
                        </div>
                    </div>
                    
                @endforeach
                
            </div>
          
            <!-- Controls -->
            <a class="left carousel-control" href="{{'#' . $carouselID2}}" role="button" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="{{'#' . $carouselID2}}" role="button" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
        </div>

    @if ($withPanel)
        
            </div>

            @if (Functions::testVar($panelFooter2))
                <div class="panel-footer">
                    {!! $panelFooter2 !!}
                </div>    
            @endif
            
        </div>
    @endif
@elseif (false)
    <div class="panel panel-default">
        <div class="panel-body">
            <h4>We Are Sorry! We have no Images!</h4>
        </div>
    </div>
@endif