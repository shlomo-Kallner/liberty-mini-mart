
<!-- 
Plans Pricing Component
    
Theme Name: Flexor
Theme URL: https://bootstrapmade.com/flexor-free-multipurpose-bootstrap-template/
Author: BootstrapMade.com
Author URL: https://bootstrapmade.com

 -->

<!-- ======== @Region: #content ======== -->
<div id="content">
    <!-- Pricing -->
    <div class="block-contained">
        <h2 class="block-title">
            Our Plans
        </h2>
        <div class="row">

            @if(isset($plans))
            
            @foreach (unserialize($plans) as $plan)
                @component('lib.bootstrapmade.price_plan')
                    
                    @foreach ($plan as $key => $value)
                        @slot($key)
                            {{ $value }}
                        @endslot
                    @endforeach
                    
                @endcomponent
            @endforeach

            @else

                <div class="col-md-3">
                    <div class="panel panel-default panel-pricing text-center">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                Flex<em>Starter</em>
                            </h4>
                        </div>
                        <div class="panel-pricing-price">$ <span class="digits">19.95</span> /mo.</div>
                        <div class="panel-body">
                            <ul class="list-dotted">
                                <li>3 User Accounts</li>
                                <li>3 Private Projects</li>
                                <li>Umlimited Projects</li>
                                <li>5GB of space</li>
                            </ul>
                            <a href="#" class="btn btn-primary btn-sm">Choose Plan</a>

                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="panel panel-default panel-pricing panel-pricing-highlighted text-center">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                Team<em>Starter</em>
                            </h4>
                        </div>
                        <div class="panel-pricing-price">$ <span class="digits">49.95</span> /mo.</div>
                        <div class="panel-body">
                            <ul class="list-dotted">
                                <li>50 User Accounts</li>
                                <li>50 Private Projects</li>
                                <li>Umlimited Projects</li>
                                <li>Unlimited space</li>
                            </ul>
                            <a href="#" class="btn btn-primary btn-sm">Choose Plan</a>

                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="panel panel-default panel-pricing panel-pricing-highlighted text-center">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                Enterprise
                                <span class="panel-pricing-popular"><i class="fa fa-thumbs-up"></i> Popular</span>
                            </h4>
                        </div>
                        <div class="panel-pricing-price">$ <span class="digits">199.95</span> /mo.</div>
                        <div class="panel-body">
                            <ul class="list-dotted">
                                <li>100 User Accounts</li>
                                <li>100 Private Projects</li>
                                <li>Umlimited Projects</li>
                                <li>Unlimited space</li>
                            </ul>
                            <a href="#" class="btn btn-primary btn-sm">Choose Plan</a>

                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="panel panel-default panel-pricing text-center">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                Corporate
                            </h4>
                        </div>
                        <div class="panel-pricing-price">$ <span class="digits">299.95</span> /mo.</div>
                        <div class="panel-body">
                            <ul class="list-dotted">
                                <li>1000 User Accounts</li>
                                <li>1000 Private Projects</li>
                                <li>Umlimited Projects</li>
                                <li>Unlimited space</li>
                            </ul>
                            <a href="#" class="btn btn-primary btn-sm">Choose Plan</a>

                        </div>
                    </div>
                </div>
    
            @endif
            
        </div>
    </div>
</div>
<!-- ======== @Region: #content ======== -->
