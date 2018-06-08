
@php

    $testing = false;

    use \App\Utilities\Functions\Functions;

    $paginator2 = Functions::getUnBladedContent($paginator??'');
    $currentRange2 = Functions::getUnBladedContent($currentRange??'','');
    $totalItems2 = intval(Functions::getBladedString($totalItems??'',''));
    $numRanges2 = intval(Functions::getBladedString($numRanges??'',''));
    $ranges2 = Functions::getUnBladedContent($ranges??'','');
    
    //dd($paginator2, $currentRange2, $totalItems2, $numRanges2, $ranges2);

    $numPerView = 4;
    $numViews = Functions::genRowsPerPage(count($ranges2), $numPerView);
    $viewIdxs = Functions::genPageArray($ranges2, $numPerView);
    dd($numPerView, $numViews, $viewIdxs);
@endphp

@if (Functions::testVar($paginator2) || $testing === true)
    <!-- BEGIN PAGINATOR -->
    <div class="row">

    @if (Functions::testVar($paginator2))
        
            <div class="col-md-4 col-sm-4 items-info">
                Items {{ $currentRange2['begin'] }} 
                to {{ $currentRange2['end'] }} 
                of {{ $totalItems2 }} total
            </div>
            <div class="col-md-8 col-sm-8">
                <ul class="pagination pull-right">
                    @if ($numRanges2 > 1 && $currentRange2['index'] > 0 )
                        <li>
                            <a href="javascript:;" aria-label="Previous">
                                <i class="fa fa-chevron-left" aria-hidden="true"></i>
                            </a>
                        </li>
                    @endif

                    @foreach ($ranges2 as $item)
                        @if ($loop->index === $currentRange2['index'])
                            <li>
                                <span>{{ $loop->index + 1 }}</span>
                            </li> 
                        
                        @else
                            <li><a href="javascript:;">{{ $loop->index + 1 }}</a></li>
                        @endif
                    @endforeach
                    
                    @if ($numRanges2 > 1 && $currentRange2['index'] < $numRanges2 )
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

    </div>
    <!-- END PAGINATOR -->
@endif
