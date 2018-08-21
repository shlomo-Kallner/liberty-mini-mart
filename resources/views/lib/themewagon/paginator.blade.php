
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
    $viewNumber2 = intval(Functions::getUnBladedContent($viewNumber??'', -1));
    $baseUrl2 = Functions::getUnBladedContent($baseUrl??'', '');
    
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
        $currentView = $viewNumber2 > 0 && $viewNumber2 < count($viewIdxs) ? $viewNumber2 : -1;
        if ($currentView == -1) {
            for ($i = 0; $i < count($viewIdxs); $i++) {
                //dd($i, $viewIdxs[$i]);
                $btmp = true;
                if ($btmp) {
                    for ($j = 0; $j < count($viewIdxs[$i]); $j++) {
                        if (in_array($currentRange2['index'], $viewIdxs[$i][$j])) {
                            $currentView = $i;
                            //dd($i, $currentView);
                            $btmp = false;
                            break;
                        } 
                    }
                } else {
                    break;
                }
            }
        }
        //dd($numPerView2, $numViews, $viewIdxs, $currentView);

        if ($currentView != -1) {

            // view urls..
            $prevViewUrl = $baseUrl2 . '?' . http_build_query(
                [
                    'viewNum' => $currentView - 1, 
                    'pageNum'=> Functions::getVar($currentRange2['index'], 1),
                    'pagingFor' => $pagingFor2
                ]
            );
            $nextViewUrl = $baseUrl2 . '?' . http_build_query(
                [
                    'viewNum' => $currentView + 1, 
                    'pageNum'=> Functions::getVar($currentRange2['index'], 1),
                    'pagingFor' => $pagingFor2 
                ]
            );
            $numberedViewUrls = [];
            //dd($prevViewUrl, $nextViewUrl, $numberedViewUrls, $viewIdxs, $currentView);
            foreach ($viewIdxs[$currentView] as $item) {
                //dd($item);
                foreach ($item as $val) {
                    //dd($item, $val);
                    $numberedViewUrls[Functions::getVar($val, 0)] = $baseUrl2 . '?' . http_build_query(
                        [
                            'viewNum' => $currentView, 
                            'pageNum'=> Functions::getVar($val, 0) + 1,
                            'pagingFor' => $pagingFor2 
                        ]
                    );
                }
            }

        } else {
            $paging = false;
        }

    } else {
        //$testing = true;
    }
    //dd($numberedViewUrls, url($nextViewUrl));
    //dd($paging, $currentRange2, $totalItems2, $ranges2, $numPerView2);
    
@endphp

@if (Functions::testVar($paging) || $testing === true)
    <!-- BEGIN PAGINATOR -->
    <div class="row">

    @if (Functions::testVar($paging))
        
            <div class="col-md-4 col-sm-4 items-info">
                Items {{  Functions::getVar($currentRange2['begin'], 0) + 1 }} 
                to {{  Functions::getVar($currentRange2['end'], 0) + 1 }} 
                of {{ $totalItems2 }} total
            </div>

            <div class="col-md-8 col-sm-8">
                <ul class="pagination pull-right">
                    @if ($numViews > 1 && $currentView > 0 )
                        <li>
                            <a href="{{ url($prevViewUrl) }}" aria-label="Previous">
                                <i class="fa fa-chevron-left"></i>
                            </a>
                        </li>
                    @endif

                    @foreach ($viewIdxs[$currentView] as $item)
                        @foreach ($item as $value)
                            <li>
                                @if (Functions::getVar($value, 0) === Functions::getVar($currentRange2['index'], 0))
                                    <span>{{ Functions::getVar($value, 0) + 1 }}</span> 
                                @else
                                    <a href="{{ url($numberedViewUrls[Functions::getVar($value, 0)]) }}">
                                        {{ Functions::getVar($value, 0) + 1 }}
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    @endforeach
                    
                    @if ($numViews > 1 && $currentView < $numViews )
                        <li>
                            <a href="{{ url($nextViewUrl) }}" aria-label="Next">
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
