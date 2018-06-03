
<!-- 
    Plans Pricing (Row) Component
        
    Theme Name: Flexor
    Theme URL: https://bootstrapmade.com/flexor-free-multipurpose-bootstrap-template/
    Author: BootstrapMade.com
    Author URL: https://bootstrapmade.com
    
-->

@php

    $testing = true;
    use \App\Utilities\Functions\Functions,
        \App\Page;


    $plansPerRow = 4;
    $currency2 = Functions::getBladedString($currency??'','fa-usd');
    if (!$testing) {
        $plans2 = Functions::getUnBladedContent($plans??'','');
    } else {
        $plans2 = [
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
        //dd($plans2);
    }
    $planRows2 = [];


@endphp

<!-- ======== @Region: #content ======== -->
<div id="content">
    <!-- Pricing -->
    <div class="block-contained">
        <h2 class="block-title">
            {{ $title2 }}
        </h2>
        @foreach ($planRows2 as $row)
            <div class="row">

                @foreach ($row as $plan)
                    @component('lib.bootstrapmade.price_plan')
                        @slot('currency')
                            {!! is_numeric($plan['price']) ? 
                                $currency2 : 'fa-thumbs-up' !!}
                        @endslot
                        
                        @foreach ($plan as $key => $value)
                            @slot($key)
                                {!! $value !!}
                            @endslot
                        @endforeach
                        
                    @endcomponent
                @endforeach
                
            </div>
        @endforeach
    </div>
</div>
<!-- ======== @Region: #content ======== -->


     