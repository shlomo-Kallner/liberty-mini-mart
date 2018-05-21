
{{-- breadcrumbs derived From metronic/.../shop-product-list.html template page. --}}

    
@php
//dd($breadcrumbs);
$testing = false;


// unserializing $breadcrumbs if its set to a non-null string..
if(isset( $breadcrumbs ) && unserialize(html_entity_decode((string)$breadcrumbs)) !== ''){
    $crumbs = unserialize(html_entity_decode((string)$breadcrumbs));
}else{
    $crumbs = [
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
    ];
        
}
@endphp

@if ($testing || !empty($crumbs['current']['name']))
<div class="row margin-bottom-5 margin-top-5">
    <ul class="breadcrumb pull-left">
            
@endif

@if( !empty($crumbs['current']['name'] ) )
    
        <li><a href="{{ url('') }}">Home</a></li>
        @if(isset($crumbs['links']) && is_array($crumbs['links']) && count($crumbs['links']) > 0  )
        @foreach ($crumbs['links'] as $breadcrumb)
            @if( !empty($breadcrumb['url']) && !empty($breadcrumb['name']) )
                <li><a href="{{ url($breadcrumb['url']) }}">{{ $breadcrumb['name'] }}</a></li>
            @endif
        @endforeach
        @endif
            
        @if ( !empty($crumbs['current']['url']) && !empty($crumbs['current']['name']) )
            <li class="active">{{$crumbs['current']['name']}}</li>
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


