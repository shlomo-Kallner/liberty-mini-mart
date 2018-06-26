
@php

    $testing = false;

    use \App\Utilities\Functions\Functions;

    //$paginator2 = Functions::getUnBladedContent($paginator??'');
    $currentRange2 = Functions::getUnBladedContent($currentRange??'','');
    $totalItems2 = intval(Functions::getUnBladedContent($totalItems??'',''));
    $numRanges2 = intval(Functions::getUnBladedContent($numRanges??'',''));
    $ranges2 = Functions::getUnBladedContent($ranges??'','');
    
    //dd($paginator2);
    //dd($currentRange2, $totalItems2, $numRanges2, $ranges2);
    //dd($currentRange, $totalItems, $numRanges, $ranges);
    //dd($ranges2);
    $paging = true;
    if ($currentRange2 == '' || $totalItems2 == '' || $numRanges2 == '' || $ranges2 == '') 
    {
        $paging = false;
    } 
    if (Functions::testVar($paging)) {
        $numPerView = 4;
        $numViews = Functions::genRowsPerPage(count($ranges2), $numPerView);
        $viewIdxs = Functions::genPageArray($ranges2, $numPerView);
        $currentView = -1;
        for ($i = 0; $i < count($viewIdxs); $i++) {
            if (in_array($currentRange2['index'],$viewIdxs[$i])) {
                $currentView = $i;
                break;
            }
        }
        //dd($numPerView, $numViews, $viewIdxs, $currentView);
    } else {
        //$testing = true;
    }

    
@endphp

@if (Functions::testVar($paging) || $testing === true)
    <!-- BEGIN PAGINATOR -->
    <div class="row">

    @if (Functions::testVar($paging))
        
            <div class="col-md-4 col-sm-4 items-info">
                Items {{ $currentRange2['begin'] + 1 }} 
                to {{ $currentRange2['end'] + 1 }} 
                of {{ $totalItems2 }} total
            </div>
            <div class="col-md-8 col-sm-8">
                <ul class="pagination pull-right">
                    @if ($numViews > 1 && $currentView > 0 )
                        <li>
                            <a href="javascript:;" aria-label="Previous">
                                <i class="fa fa-chevron-left"></i>
                            </a>
                        </li>
                    @endif

                    @foreach ($viewIdxs[$currentView] as $item)
                        <li>
                            @if ($item === $currentRange2['index'])
                                <span>{{ $item + 1 }}</span> 
                            @else
                                <a href="javascript:;">{{ $item + 1 }}</a>
                            @endif
                        </li>
                    @endforeach
                    
                    @if ($numViews > 1 && $currentView < $numViews )
                        <li>
                            <a href="javascript:;" aria-label="Next">
                                    <i class="fa fa-chevron-right"></i>
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
