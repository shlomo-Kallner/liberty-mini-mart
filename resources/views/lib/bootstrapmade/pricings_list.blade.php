
<!-- 
    Plans Pricing (Row) Component
        
    Theme Name: Flexor
    Theme URL: https://bootstrapmade.com/flexor-free-multipurpose-bootstrap-template/
    Author: BootstrapMade.com
    Author URL: https://bootstrapmade.com
    
-->

@php

    $testing = false;
    use \App\Utilities\Functions\Functions,
        \App\Page;


    $plansPerRow = 4;
    $currency2 = Functions::getBladedString($currency??'','fa-usd');
    $title2 = Functions::getBladedString($title??'','OUR PLANS');
    if (!$testing) {
        $plans2 = Functions::getUnBladedContent($plans??'','');
        //dd($plans);
    } else {
        $plansTmp = [
            [
                'name' => e('Flex<em>Starter</em>'),
                'popular' => '',
                'price' => '19.95',
                'details' => serialize([
                    '3 User Accounts',
                    '3 Private Projects',
                    'Umlimited Projects',
                    '5GB of space'
                ]),
                'buttons' => serialize([
                    /*
                        function genURLMenuItem(
                            string $url, string $name, string $icon = '', 
                            string $textTransform = '', string $cssExtraClasses = ''
                        )
                    */
                    Page::genURLMenuItem('#','Choose Plan', '','','btn btn-primary btn-sm'),
                ]),
            ],
            [
                'name' => e('Team<em>Starter</em>'),
                'popular' => '',
                'price' => '49.95',
                'details' => serialize([
                    '50 User Accounts',
                    '50 Private Projects',
                    'Umlimited Projects',
                    'Unlimited space',
                    
                ]),
                'buttons' => serialize([
                    /*
                        function genURLMenuItem(
                            string $url, string $name, string $icon = '', 
                            string $textTransform = '', string $cssExtraClasses = ''
                        )
                    */
                    Page::genURLMenuItem('#','Choose Plan', '','','btn btn-primary btn-sm'),
                ]),
            ],
            [
                'name' => 'Enterprise',
                'popular' => 'true',
                'price' => '199.95',
                'details' => serialize([
                    '100 User Accounts',
                    '100 Private Projects',
                    'Umlimited Projects',
                    'Unlimited space'
                ]),
                'buttons' => serialize([
                    /*
                        function genURLMenuItem(
                            string $url, string $name, string $icon = '', 
                            string $textTransform = '', string $cssExtraClasses = ''
                        )
                    */
                    Page::genURLMenuItem('#','Choose Plan', '','','btn btn-primary btn-sm'),
                ]),
            ],
            [
                'name' => 'Corporate',
                'popular' => '',
                'price' => '299.95',
                'details' => serialize([
                    '1000 User Accounts',
                    '1000 Private Projects',
                    'Umlimited Projects',
                    'Unlimited space'
                ]),
                'buttons' => serialize([
                    /*
                        function genURLMenuItem(
                            string $url, string $name, string $icon = '', 
                            string $textTransform = '', string $cssExtraClasses = ''
                        )
                    */
                    Page::genURLMenuItem('#','Choose Plan', '','','btn btn-primary btn-sm'),
                ]),
            ],
        ];
        $plans2 = Functions::genMultipleFromArray($plansTmp, 8);
        //dd($plans2);
    }
    $sorting2 = Functions::getBladedContent($sorting??'','');
    // $pageNumber is problematic.. 
    // our defualt should be '-1' (show all) but to show the first
    // page we cannot pass '0' because it's a null string value.. 
    // and empty() will evaluate it to being empty/null.. 
    $pageNumber2 = intval(Functions::getBladedString($pageNumber??0,0)) -1;
    //dd($pageNumber2);
    // our default.. is 16 plans per page (the template had 4 per row..)
    $plansPerPage2 = intval(Functions::getBladedString($plansPerPage??16,16));
    //dd($plansPerPage2);

    if (Functions::testVar($plans2)) {

        $totalPlans = count($plans2);
        $rowIdxs = Functions::genPagesIndexes($plansPerPage2, $plansPerRow, $totalPlans, $pageNumber2);

        // initializing the paginator
        $numPages = Functions::genRowsPerPage($totalPlans, $plansPerPage2);
        //dd($pageNumber2, $totalPlans, $plansPerPage2, $rowIdxs, $numPages);
        if ($testing) {
            $tmp = [
                'level 0' => [
                    $rowIdxs,
                    count($rowIdxs),
                ],
                'level 1' => [
                    $rowIdxs[0],
                    count($rowIdxs[0]),
                ],
                'level 2' => [
                    $rowIdxs[0][0],
                    count($rowIdxs[0][0]),
                ],
                'level 3' => [
                    $rowIdxs[0][0][0],
                    //count($rowIdxs[0][0][0])
                ]
            ];
        }
        
        $lastIndexes = [
            count($rowIdxs)-1,
            count($rowIdxs[count($rowIdxs)-1])-1,
            count($rowIdxs[count($rowIdxs)-1][count($rowIdxs[count($rowIdxs)-1])-1])-1,
        ];
        //dd($pageNumber2, $totalPlans, $plansPerPage2, $rowIdxs, $numPages, $lastIndexes);
        $paginator = [
            'totalItems' => $totalPlans,
            'numRanges' => $numPages, // count($rowIdxs), // 
            'ranges' => serialize(Functions::genRange(0, $numPages-1, 1)), // serialize(Functions::genRange(0, count($rowIdxs)-1, 1)),
            'currentRange' => serialize([
                'begin' => $rowIdxs[0][0][0],
                'end' => $rowIdxs[$lastIndexes[0]][$lastIndexes[1]][$lastIndexes[2]],
                'index' => $pageNumber2 > -1 ? $pageNumber2 : 0,
            ]),
        ];
        //dd($paginator);
        
    } else {
        $paginator = [];
        $rowIdxs = [];
    }


@endphp

<!-- ======== @Region: #content ======== -->
<div id="content">
    <!-- Pricing -->
    <div class="block-contained">
        <h2 class="block-title">
            {{ $title2 }}
        </h2>

        @if (Functions::testVar($sorting2))
            {{-- 
                inspired by Metronic Shop UI 
                - (lib.themewagon.content_list.blade.php) 
            --}}
            
            <div class="row">
                <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                    <div class="pull-right">
                        
                        <label for="showNumPricing-slc" class="col-sm-2">Show:</label>
                        <select name="showNumPricing" id="showNumPricing-slc" class="form-control input-sm">
                            <option value="#?limit=24" selected="selected">24</option>
                            <option value="#?limit=25">25</option>
                            <option value="#?limit=50">50</option>
                            <option value="#?limit=75">75</option>
                            <option value="#?limit=100">100</option>
                        </select>
                        
                    </div>
                    <div class="pull-right">
                        
                            <label for="pricingSorting-slc" class="col-sm-2">Sort&nbsp;By:</label>
                            <select name="pricingSorting" id="pricingSorting-slc" class="form-control input-sm">
                                <option value="#?sort=p.sort_order&amp;order=ASC" selected="selected">Default</option>
                                <option value="#?sort=pd.name&amp;order=ASC">Name (A - Z)</option>
                                <option value="#?sort=pd.name&amp;order=DESC">Name (Z - A)</option>
                                <option value="#?sort=p.price&amp;order=ASC">Price (Low &gt; High)</option>
                                <option value="#?sort=p.price&amp;order=DESC">Price (High &gt; Low)</option>
                                <option value="#?sort=rating&amp;order=DESC">Rating (Highest)</option>
                                <option value="#?sort=rating&amp;order=ASC">Rating (Lowest)</option>
                            </select>
                            
                    </div>
                </div>
            </div>
            
            
        @endif

        @foreach ($rowIdxs as $page)
            @foreach ($page as $row)
                <div class="row">

                    @foreach ($row as $idx)
                        @php
                            //dd($rowIdxs,$page,$row,$idx,$plans2);
                        @endphp
                        @if ($idx < count($plans2))
                            @component('lib.bootstrapmade.price_plan')
                                @slot('currency')
                                    {!! is_numeric($plans2[$idx]['price']) ? 
                                        $currency2 : 'fa-thumbs-up' !!}
                                @endslot
                                
                                @foreach ($plans2[$idx] as $key => $value)
                                    @slot($key)
                                        {!! $value !!}
                                    @endslot
                                @endforeach
                                
                            @endcomponent
                        @endif

                    @endforeach
                    
                </div>
            @endforeach
        @endforeach
        
        
        @if (Functions::testVar($paginator))
            @component('lib.themewagon.paginator')
                @slot('paginator')
                    {!! serialize($paginator) !!}
                @endslot
                @foreach ($paginator as $key => $item)
                    @slot($key)
                        {!! $item !!}
                    @endslot
                @endforeach
            @endcomponent
        @endif
                
    </div>
    
</div>
<!-- ======== @Region: #content ======== -->




     