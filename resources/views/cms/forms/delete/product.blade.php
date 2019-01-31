@extends('cms.forms.delete.template')

@section('form-content')
    @parent

    @php
        $testing = true;    
        use \App\Utilities\Functions\Functions,
        \App\Page;

        $price2 = Functions::getBladedString($page['price']??'', old('price'));
        $sale2 = Functions::getBladedString($page['sale']??'', old('sale'));
        $sectionList2 = Functions::getContent($page['lists']['sections']??'', '');
        $hasSelectedSection2 = Functions::getBladedString($page['hasSelectedSection']??'', '');
        $selectedSection2 = Functions::getContent(
        $page['selectedSection']??'', Page::makeNameListing('No Section', '')
    );
        
    @endphp
    
    <h3>Price: </h3>
    <div class="well well-sm">
        <h3>{{ $price2 }}</h3> 
    </div>

    <h3>Sale: </h3>
    <div class="well well-sm">
        <h3>{{ $sale2 }}</h3> 
    </div>

    <h3>Section:</h3>
    <div class="well well-sm">
        <h3>
            @component('lib.themewagon.links')
                @foreach (Page::genURLMenuItem($selectedSection2['url'], $selectedSection2['name']) as $key => $item)
                    @slot($key)
                        {!! Functions::toBladableContent($item) !!}
                    @endslot
                @endforeach
            @endcomponent
        </h3>
    </div>    
                    
@endsection
