
@php

    $testing = false;

    use \App\Utilities\Functions\Functions;
    use \App\Page;
    use \Illuminate\Support\Collection;

    // $numRanges2 = intval(Functions::getUnBladedContent($numRanges??'',''));    
    // $paginator2 = Functions::getUnBladedContent($paginator??'');
    // $currentRange2 = Functions::getUnBladedContent($currentRange??'','');
    // $ranges2 = Functions::getUnBladedContent($ranges??'','');
    //dd($paginator2);
    //dd($currentRange2, $totalItems2, $ranges2);
    //dd($currentRange, $totalItems, $ranges);
    //dd($numRanges2, $numRanges);
    //dd($ranges2);    
        
    $firstItemIndex2 = intval(Functions::getBladedString($firstItemIndex??'', 0));
    $lastItemIndex2 = intval(Functions::getBladedString($lastItemIndex??'', 0));
    $numItemsPerPage2 = intval(Functions::getBladedString($numItemsPerPage??'', 0));
    $totalItems2 = intval(Functions::getBladedString($totalItems??'', -1));

    $currentPage2 = intval(Functions::getBladedString($currentPage??'', 0));
    $totalNumPages2 = intval(Functions::getBladedString($totalNumPages??'', -1));
    $numPagesPerView2 = intval(Functions::getBladedString($numPerView??'', '4'));
    $viewNumber2 = intval(Functions::getBladedString($viewNumber??'', 0));
    
    $pagingFor2 = Functions::getBladedString($pagingFor??'', 'null');
    $baseUrl2 = Functions::getBladedString($baseUrl??'', '');

    if (($currentPage2 === -1) || ($totalItems2 === -1) 
    || ($firstItemIndex2 === -1) || ($lastItemIndex2 === -1) 
    || ($totalNumPages2 === -1) || ($numPagesPerView2 === -1) 
    ) {
        $paging = false;
    } else {
        $paging = true;
        
        $numViews = Functions::genRowsPerPage(
            $totalNumPages2, $numPagesPerView2
        );
        $viewRangeIdx = Page::genFirstAndLastItemsIdxes( 
            $totalNumPages2, $viewNumber2, $numPagesPerView2
        );
        //$viewIdxs = Functions::genPageArray($ranges2, $numPerView2);
        $viewIdxs = Functions::genRange(
            $viewRangeIdx['begin'], 
            $viewRangeIdx['end'] - 1
        );
        $currentView = $viewNumber2 >= 0 && $viewNumber2 < $numViews
            ? $viewNumber2 
            : -1;
        //dd($numPagesPerView2, $totalNumPages2, $numViews, $viewIdxs, $currentView, $viewRangeIdx);

        if ($currentView != -1) {

            // view urls..
            $prevViewUrl = $baseUrl2 . '?' . http_build_query(
                [
                    'viewNum' => $currentView - 1, 
                    'pageNum'=> Functions::getVar($currentPage2, 1),
                    'pagingFor' => $pagingFor2
                ]
            );
            $nextViewUrl = $baseUrl2 . '?' . http_build_query(
                [
                    'viewNum' => $currentView + 1, 
                    'pageNum'=> Functions::getVar($currentPage2, 1),
                    'pagingFor' => $pagingFor2 
                ]
            );
            $numberedViewUrls = [];
            //dd($prevViewUrl, $nextViewUrl, $numberedViewUrls, $viewIdxs, $currentView);
            //dd($viewRangeIdx, $viewIdxs, $currentView, $numPagesPerView2, 'paginator1.blade.php');
            foreach ($viewIdxs as $idx) {
                //dd($item, is_int($item), is_array($item));
                $numberedViewUrls[$idx] = $baseUrl2 . '?' . http_build_query(
                    [
                        'viewNum' => $currentView, 
                        'pageNum'=> Functions::getVar($idx, 0), // + 1,
                        'pagingFor' => $pagingFor2 
                    ]
                );
            }

        } else {
            $paging = false;
        }

        if (false) {
            dd(
                $firstItemIndex2, $lastItemIndex2, $totalItems2, 
                $currentPage2, $totalNumPages2, $numPagesPerView2,
                $numViews, $viewRangeIdx, $viewIdxs, $currentView
            );
        }

    } 
    //dd($numberedViewUrls, url($nextViewUrl));
    //dd($paging, $currentRange2, $totalItems2, $ranges2, $numPerView2);
    
@endphp

@if (Functions::testVar($paging) || $testing === true)
    <!-- BEGIN PAGINATOR -->
    <div class="row">

    @if (Functions::testVar($paging))
        
            <div class="col-md-4 col-sm-4 items-info">
                Items {{ Functions::getVar($firstItemIndex2, 0) + 1 }} 
                to {{ Functions::getVar($lastItemIndex2, 0) + 1 }} 
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

                    @foreach ($viewIdxs as $idx)
                        <li>
                            @if (Functions::getVar($idx, 0) === Functions::getVar($currentPage2, 0))
                                <span>{{ Functions::getVar($idx, 0) + 1 }}</span> 
                            @else
                                <a href="{{ url($numberedViewUrls[Functions::getVar($idx, 0)]) }}">
                                    {{ Functions::getVar($idx, 0) + 1 }}
                                </a>
                            @endif
                        </li>
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
