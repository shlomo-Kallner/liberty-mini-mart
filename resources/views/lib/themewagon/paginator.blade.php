
@php

    $testing = true;

    use \App\Utilities\Functions\getBladedContent,
        \App\Utilities\Functions\testVar;

    $paginator2 = getBladedContent($paginator);
    
@endphp

@if (testVar($paginator2) || $testing === true)
    <!-- BEGIN PAGINATOR -->
    <div class="row">
    
@endif

@if (testVar($paginator2))
    
        <div class="col-md-4 col-sm-4 items-info">
            Items {{$paginator2['currentRange']['begin']}} 
            to {{$paginator2['currentRange']['end']}} 
            of {{$paginator2['totalItems']}} total
        </div>
        <div class="col-md-8 col-sm-8">
            <ul class="pagination pull-right">
                @if ($paginator2['numRanges'] > 1 && $paginator2['currentRange']['index'] > 0 )
                    <li>
                        <a href="javascript:;" aria-label="Previous">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        </a>
                    </li>
                @endif

                @foreach ($paginator2['ranges'] as $item)
                    @if ($loop->index === $paginator2['currentRange']['index'])
                        <li>
                            <span>{{ $loop->index + 1 }}</span>
                        </li> 
                    
                    @else
                        <li><a href="javascript:;">{{ $loop->index + 1 }}</a></li>
                    @endif
                @endforeach
                
                @if ($paginator2['numRanges'] > 1 
                 && $paginator2['currentRange']['index'] < $paginator2['numRanges'] )
                    <li>
                        <a href="javascript:;" aria-label="Next">
                                <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

@elseif ($testing)

        <div class="col-md-4 col-sm-4 items-info">Items 1 to 9 of 10 total</div>
        <div class="col-md-8 col-sm-8">
            <ul class="pagination pull-right">
                <li>
                    <a href="javascript:;" aria-label="Previous">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    </a>
                </li>
                <li><a href="javascript:;">1</a></li>
                <li><span>2</span></li>
                <li><a href="javascript:;">3</a></li>
                <li><a href="javascript:;">4</a></li>
                <li><a href="javascript:;">5</a></li>
                <li>
                    <a href="javascript:;" aria-label="Next">
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    </a>
                </li>
            </ul>
        </div>
    
@endif

@if (testVar($paginator2) || $testing === true)    
    </div>
    <!-- END PAGINATOR -->
@endif
