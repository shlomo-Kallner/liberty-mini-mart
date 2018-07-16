
@php
        
    $testing = true;
    use \App\Utilities\Functions\Functions;

    $products2 = Functions::getUnBladedContent($products??'');
    $section_url2 = Functions::getBladedString($section_url??'');
    $category_url2 = Functions::getBladedString($category_url??'');
    $paginator2 = Functions::getUnBladedContent($paginator??'');
    
    $panelGroupId = 'products-panel-group-of-' . $category_url2 . '-in-' . $section_url2;

@endphp

<div class="panel-group" id="{{$panelGroupId}}" role="tablist" aria-multiselectable="true">

    @forelse ($products2 as $product)

        @php
            
        @endphp

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="{{'headingOne'}}">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="{{'#' . $panelGroupId}}" href="{{'#'. 'collapseOne'}}" aria-expanded="true" aria-controls="{{'collapseOne'}}">
                        Collapsible Group Item #1
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{'headingOne'}}">
                <div class="panel-body">
                    
                </div>
            </div>
        </div>

    @empty
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>We Are Sorry! There are no Products for this Category!</h4>
            </div>
        </div>
    @endforelse

    @if (Functions::testVar($paginator2) && Functions::testVar($products2))
        <div class="panel panel-default">
            <div class="panel-body">
                @component('lib.themewagon.paginator')
                    @foreach ($paginator2 as $key => $val)
                        @slot($key)
                            {!! serialize($val) !!}
                        @endslot
                    @endforeach
                    @slot('pagingFor')
                        {!! 'admin.Section-'. $section_url2 . '.Category-' . $category_url2 .  ' .ProductsPanel' !!}
                    @endslot
                @endcomponent
            </div>
        </div>
    @endif
        
</div>