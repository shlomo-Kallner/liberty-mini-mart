
@php

    $testing = false;

    use \App\Utilities\Functions\Functions;

    //$paginator2 = Functions::getUnBladedContent($paginator??'');
    $currentRange2 = Functions::getUnBladedContent($currentRange??'','');
    $totalItems2 = intval(Functions::getUnBladedContent($totalItems??'',''));
    //$numRanges2 = intval(Functions::getUnBladedContent($numRanges??'',''));
    $ranges2 = Functions::getUnBladedContent($ranges??'','');
    $numPerView2 = intval(Functions::getUnBladedContent($numPerView??'','4'));
    $pagingFor2 = Functions::getBladedString($pagingFor??'','');

    
    //dd($paginator2);
    //dd($currentRange2, $totalItems2, $ranges2);
    //dd($currentRange, $totalItems, $ranges);
    //dd($numRanges2, $numRanges);
    //dd($ranges2);
    $paging = true;
    if ($currentRange2 == '' || $totalItems2 == '' || $ranges2 == '' || $numPerView2 == '') // || $numRanges2 == '' 
    {
        $paging = false;
    } 
    //dd($paging, $currentRange2, $totalItems2, $ranges2, $numPerView2);
    if (Functions::testVar($paging)) {
        //$numPerView = Functions::testVar($numPerView2) ? $numPerView2 : 4;
        $numViews = Functions::genRowsPerPage(count($ranges2), $numPerView2);
        $viewIdxs = Functions::genPageArray($ranges2, $numPerView2);
        $currentView = -1;
        for ($i = 0; $i < count($viewIdxs); $i++) {
            if (in_array($currentRange2['index'], $viewIdxs[$i])) {
                $currentView = $i;
                //dd($i, $currentView);
                break;
            }
        }
        dd($numPerView2, $numViews, $viewIdxs, $currentView);

        if ($currentView != -1) {

            // view urls..
            $prevViewUrl = '#?' . http_build_query(
                [
                    'viewNum' => $currentView - 1, 
                    'pageNum'=> Functions::getVar($currentRange2['index'], 1),
                    'pagingFor' => $pagingFor2
                ]
            );
            $nextViewUrl = '#?' . http_build_query(
                [
                    'viewNum' => $currentView + 1, 
                    'pageNum'=> Functions::getVar($currentRange2['index'], 1),
                    'pagingFor' => $pagingFor2 
                ]
            );
            $numberedViewUrls = [];
            //dd($prevViewUrl, $nextViewUrl, $numberedViewUrls, $viewIdxs, $currentView);
            foreach ($viewIdxs[$currentView] as $item) {
                $numberedViewUrls[$item] = '#?' . http_build_query(
                    [
                        'viewNum' => $currentView, 
                        'pageNum'=> $item + 1,
                        'pagingFor' => $pagingFor2 
                    ]
                );
            }

        } else {
            $paging = false;
        }

    } else {
        //$testing = true;
    }
    dd($paging, $currentRange2, $totalItems2, $ranges2, $numPerView2);
    
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
                            <a href="{{ $prevViewUrl }}" aria-label="Previous">
                                <i class="fa fa-chevron-left"></i>
                            </a>
                        </li>
                    @endif

                    @foreach ($viewIdxs[$currentView] as $item)
                        <li>
                            @if ($item === $currentRange2['index'])
                                <span>{{ $item + 1 }}</span> 
                            @else
                                <a href="{{ $numberedViewUrls[$item] }}">{{ $item + 1 }}</a>
                            @endif
                        </li>
                    @endforeach
                    
                    @if ($numViews > 1 && $currentView < $numViews )
                        <li>
                            <a href="{{ $nextViewUrl }}" aria-label="Next">
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
