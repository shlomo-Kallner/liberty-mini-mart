
{{-- breadcrumbs derived From metronic/.../shop-product-list.html template page. --}}

    
@php
//dd($breadcrumbs);
$testing = false;
use \App\Utilities\Functions\Functions;


// unserializing $breadcrumbs if its set to a non-null string..
$crumbs = Functions::getUnBladedContent($breadcrumbs??'', [
    'links' => [
        [
            'name' => '',
            'url' => '',
        ],
    ],
    'current'=> [
        'name' => '',
        'url' => '',
    ],
]);

//dd($crumbs);

@endphp

@if ($testing || !empty($crumbs['current']['name']))
<div class="row padding-top-5">
    <ul class="breadcrumb pull-left">
            
@endif

@if( !empty($crumbs['current']['name']) )
    
        <li><a href="{{ url('') }}">Home</a></li>
        @if(Functions::testVar($crumbs['links']) && is_array($crumbs['links']) )
            @foreach ($crumbs['links'] as $breadcrumb)
                @if( !empty($breadcrumb['url']) && !empty($breadcrumb['name']) && $breadcrumb['name'] != 'Home' )
                    <li><a href="{{ url($breadcrumb['url']) }}">{!! $breadcrumb['name'] !!}</a></li>
                @endif
            @endforeach
        @endif
            
        @if ( !empty($crumbs['current']['url']) && !empty($crumbs['current']['name']) 
            && $crumbs['current']['url'] != 'home' && $crumbs['current']['name'] != 'Home'
        )
            <li class="active">{!! $crumbs['current']['name'] !!}</li>
        @endif
                
@elseif($testing)
                
        <li><a href="{{ url('') }}">Home</a></li>
        <li><a href="{{ url('store') }}">Store</a></li>
        <li class="active">Terms &amp; Conditions</li>  
            
@endif

@if ( $testing || !empty($crumbs['current']['name']) )
    </ul>   
</div>   
@endif


