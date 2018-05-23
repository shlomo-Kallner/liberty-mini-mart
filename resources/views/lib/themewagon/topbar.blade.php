
@php
use \App\Page,
    \App\Utilities\Functions\Functions;

    
$preheader2 = Functions::getBladedContent(isset($preheader)?$preheader:'');
//dd($preheader2);
if (Functions::testVar($preheader2)) {
    $preheader2 = Page::getPreHeader();
}

// $currencies2 = Functions::getBladedContent(!isset($currencies)?$currencies:'');
// $langs2 = Functions::getBladedContent(!isset($langs)?$langs:'');
@endphp

<!-- BEGIN TOP BAR -->
<div class="pre-header">
    <div class="container">
        <div class="row">
            <!-- BEGIN TOP BAR LEFT PART -->
            <div class="col-md-6 col-sm-6 additional-shop-info">
                
                {{-- 
                     TO DO: Convert CURRENCIES LIST 
                     and LANGS LIST to dynamic menu 
                     lists using our links component. 
                --}}
                <ul class="list-unstyled list-inline">
                    {{-- REMOVED: the Phone link from 
                        the template was removed. --}}
                    <!-- BEGIN CURRENCIES -->
                    <li class="shop-currencies">
                        
                        @if (false)
                                
                            {{-- the component caling code for $currencies.. 
                                @foreach ($currencies2['others'] as $item)
                                    @component('lib.themewagon.links')
                                        @slot('type')
                                            {{ 'url' }}
                                        @endslot
                                        @slot('url')
                                            {!! 'javascript:void(0);' !!}
                                        @endslot
                                        @slot('icon')
                                            {{ $item }}
                                        @endslot
                                    @endcomponent
                                @endforeach
                                <a href="javascript:void(0);" class="current">
                                    <i class="fa {{$currencies2['current']}}"></i>
                                </a>
                                
                            --}}
                        
                        @else
                            {{-- Euro --}}                        
                            <a href="javascript:void(0);">
                                <i class="fa fa-eur"></i>
                            </a>
                            
                            {{-- British Pounds --}}
                            <a href="javascript:void(0);">
                                <i class="fa fa-gbp"></i>
                            </a>
                            
                            {{-- Israeli Shekels --}}
                            <a href="javascript:void(0);">
                                <i class="fa fa-ils"></i>
                            </a>
    
                            {{-- USA Dollars - the default --}}
                            <a href="javascript:void(0);" class="current">
                                <i class="fa fa-usd"></i>
                            </a>
                        @endif

                    </li>
                    <!-- END CURRENCIES -->
                    <!-- BEGIN LANGS -->
                    <li class="langs-block">
                        @if (false)
                            
                            {{-- the component caling code for $langs.. 
                                <a href="javascript:void(0);" class="current">{{$langs2['current']}}</a>
                                <div class="langs-block-others-wrapper">
                                    <div class="langs-block-others">
                                        @foreach ($langs2['others'] as $item)
                                            @component('lib.themewagon.links')
                                                @slot('type')
                                                    {{ 'url' }}
                                                @endslot
                                                @slot('url')
                                                    {!! 'javascript:void(0);' !!}
                                                @endslot
                                                @slot('name')
                                                    {{ $item }}
                                                @endslot
                                            @endcomponent
                                        @endforeach
                                    </div>
                                </div>
                                
                            --}}
                            
                        @else
                            <a href="javascript:void(0);" class="current">English</a>
                            <div class="langs-block-others-wrapper">
                                <div class="langs-block-others">
                                    <a href="javascript:void(0);">Hebrew</a>
                                    <a href="javascript:void(0);">French</a>
                                    <a href="javascript:void(0);">Germany</a>
                                    <a href="javascript:void(0);">Turkish</a>
                                </div>
                            </div>
                        @endif
                    </li>
                    <!-- END LANGS -->
                </ul>
            </div>
            <!-- END TOP BAR LEFT PART -->
            <!-- BEGIN TOP BAR MENU -->
            <!-- 
                 While the Primary/Main template for this TOP BAR MENU 
                 is Metronic Shop UI, the Internal Styling of individual
                 menu items is inspired by/copied from 
                 'bootstrapious/universal-1-0' TOP BAR MENU.
            -->
            <div class="col-md-6 col-sm-6 additional-nav">
                <ul class="list-unstyled list-inline pull-right">
                    @if(Functions::testVar($preheader2))
                    {{-- UPDATE: Copying FA icon and span tag link from 
                                 master_bootstrapious.blade.php  TOP BAR Section
                                 to replace the 'simple' text content of these 
                                 couple of links...
                     --}}

                        @foreach($preheader2 as $nav)

                            @component('lib.themewagon.links')
                                @foreach ($nav as $key => $value)

                                    @slot($key)
                                        {{ $value }}
                                    @endslot
                                    
                                @endforeach
                            @endcomponent
                        
                        @endforeach
                    {{-- End dynamicly generated "main level" topbar menu --}}
                    @endif

                </ul>
            </div>
            <!-- END TOP BAR MENU -->
        </div>
    </div>        
</div>
<!-- END TOP BAR -->