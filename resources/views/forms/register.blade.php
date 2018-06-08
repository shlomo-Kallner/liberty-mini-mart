
@extends('content.template')

@section('main-content')
    @parent

    @php
    $testing = true;
    use \App\Utilities\Functions\Functions,
        \App\Page;

    if ($testing) {
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
        $plans2 = [
            'title' => e(''),
            'plans' => serialize(Functions::genMultipleFromArray($plansTmp, 8)),
            'sorting' => serialize([]),
            'pageNumber' => e('1'),
            'plansPerPage' => e('4'),
        ];
        //dd($plans2);
        
    } else {

    }
    $currency2 = Functions::getBladedString($currency??'','fa-usd');
    

    
    @endphp
    
    <!-- BEGIN CONTENT -->
    <div class="row margin-bottom-40">
        <!-- BEGIN CONTENT -->
        <div class="col-md-12 col-sm-12">
            <h1>New Customer? Sign up right here!</h1>
            <!-- BEGIN REGISTRATION PAGE -->
            <div class="panel-group checkout-page accordion scrollable" id="checkout-page">

            <!-- BEGIN REGISTRATION -->
            <div id="checkout" class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">
                        <a data-toggle="collapse" data-parent="#checkout-page" href="#checkout-content" class="accordion-toggle">
                            Step 1: Choose Your Account&apos;s Plan:
                        </a>
                    </h2>
                </div>
                <div id="checkout-content" class="panel-collapse collapse in">
                    <div class="panel-body row">
                        <div class="col-md-6 col-sm-6">
                        <h3>New Customer? Check Out Our Latest Plans!</h3>
                        <p>By creating an account you will be able to shop faster, be up to date on an order&apos;s status, and keep track of the orders you have previously made.</p>
                        @component('lib.bootstrapmade.pricings_list')
                            @foreach ($plans2 as $key => $item)
                                @slot($key)
                                    {!! $item !!}
                                @endslot
                                @slot('currency')
                                    {!! $currency2 !!}
                                @endslot
                            @endforeach
                        @endcomponent

                        <button class="btn btn-primary" type="submit" data-toggle="collapse" data-parent="#checkout-page" data-target="#payment-address-content">Continue</button>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- END CHECKOUT -->

            <!-- BEGIN PAYMENT ADDRESS -->
            <div id="payment-address" class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">
                        <a data-toggle="collapse" data-parent="#checkout-page" href="#payment-address-content" class="accordion-toggle">
                            Step 2: Account Details
                        </a>
                    </h2>
                </div>
                <div id="payment-address-content" class="panel-collapse collapse">
                    <div class="panel-body row">
                        <div class="col-md-6 col-sm-6">
                            <h3>Your Personal Details</h3>
                            <div class="form-group">
                                <label for="firstname">First Name <span class="require">*</span></label>
                                <input type="text" id="firstname" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="lastname">Last Name <span class="require">*</span></label>
                                <input type="text" id="lastname" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="email">E-Mail <span class="require">*</span></label>
                                <input type="text" id="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="telephone">Telephone <span class="require">*</span></label>
                                <input type="text" id="telephone" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="fax">Fax</label>
                                <input type="text" id="fax" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <h3>Choose Your Password</h3>
                            <div class="form-group">
                                <label for="password">Password <span class="require">*</span></label>
                                <input type="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password-confirm">Password Confirm <span class="require">*</span></label>
                                <input type="text" id="password-confirm" class="form-control">
                            </div>
                            <hr>                      
                            <div class="checkbox">
                                <label>
                                <input type="checkbox"> I wish to subscribe to the OXY newsletter. 
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                <input type="checkbox"> I have read and agree to the <a title="Privacy Policy" href="javascript:;">Privacy Policy</a> &nbsp;&nbsp;&nbsp; 
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                <input type="checkbox"> I have read and agree to the <a title="Terms &amp; Conditions" href="javascript:;">Terms &amp; Conditions </a> &nbsp;&nbsp;&nbsp; 
                                </label>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <button class="btn btn-primary  pull-right" type="submit" data-toggle="collapse" data-parent="#checkout-page" data-target="#shipping-address-content" id="button-payment-address">Sign Up!</button>                          
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PAYMENT ADDRESS -->

            </div>
            <!-- END CHECKOUT PAGE -->
        </div>
        <!-- END CONTENT -->
        
    </div>
    <!-- END CONTENT -->

@endsection