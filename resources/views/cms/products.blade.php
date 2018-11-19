
@php
        
    $testing = true;
    use \App\Utilities\Functions\Functions;
    use \Illuminate\Support\HtmlString;
    use Illuminate\Contracts\Support\Htmlable;

    $products2 = Functions::getUnBladedContent($products??'');
    $section_url2 = Functions::getBladedString($section_url??'');
    $category_url2 = Functions::getBladedString($category_url??'');
    $paginator2 = Functions::getUnBladedContent($paginator??'');
    
    $panelGroupId = 'products-panel-group-of-' . $category_url2 . '-in-' . $section_url2;

@endphp

<div class="panel-group" id="{{$panelGroupId}}" role="tablist" aria-multiselectable="true">

    @forelse ($products2 as $product)

        @php
            $panelId1 = 'headingProductPanel' . $category_url2 . '-of-' . $section_url2;
            $panelId2 = 'collapseProductPanel' . $category_url2 . '-of-' . $section_url2;
            $panelId3 = 'productContentCollapsedDiv' . $category_url2 . '-of-' . $section_url2;

            $base_url = 'admin/section/' . $section_url2 . '/category/' . $category_url2 . '/product/';

            $productEditUrl = $base_url . $product['url'] . '/edit';
            $productDeleteUrl = $base_url . $product['url'] . '/delete';
            

        @endphp

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="{{ $panelId1 }}">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="{{'#' . $panelGroupId}}" href="{{'#'. $panelId2}}" aria-expanded="true" aria-controls="{{$panelId2}}">
                        {!! $product['name'] !!}
                    </a>
                </h4>
            </div>
            <div id="{{ $panelId2 }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{$panelId1}}">
                <div class="panel-body">
                    

                        <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                            @if (true)
                                <div class="thumbnail">
                                    <img src="{{ asset($product['img']['img']) }}" 
                                        class="img-responsive" alt="{{$product['img']['alt']}}"
                                    >
                                    <div class="caption">
                                        {!! $product['img']['cap'] !!}
                                    </div>
                                </div>
                            @else
                                @component('inc.figure')
                                    @foreach ($product['img'] as $key => $item)
                                        @slot($key)
                                            {!! $item !!}
                                        @endslot
                                    @endforeach
                                @endcomponent
                            @endif
                        </div>
                        
                        <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                            <div class="row">
                                <h4>{!! $category['title'] !!}</h4>
                            </div>
                            
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="btn-group pull-left">
                                        <a class="btn btn-default" data-toggle="collapse" href="{{'#' . $panelId3}}" aria-expanded="false" aria-controls="{{ $panelId3 }}" role="button">Show Products</a>
                                        <a class="btn btn-primary" href="{{ $newProductCreateUrl }}" role="button">Create a new Product</a>
                                        <a class="btn btn-warning" href="{{ $categoryEditUrl }}" role="button">Edit this Category</a>
                                        <button type="button" class="btn btn-danger" onclick="deleteCategory('{{$categoryDeleteUrl}}')">Delete this Category</button>
                                        
                                        {{-- <a class="btn btn-default" href="#" role="button"></a> --}}
                                        {{-- <a class="btn btn-default" href="#" role="button"></a> --}}
                                        {{-- <a class="btn btn-default" href="#" role="button"></a> --}}
                                        
                                    </div>
                                    
                                    <div class="btn-group pull-right">
                                        <button type="button" class="btn btn-default">{{ !$category['visible'] ? 'Show' : 'Hide' }}</button>
                                        <button type="button" class="btn btn-default">Move Up</button>
                                        <button type="button" class="btn btn-default">Move Down</button>
                                    </div>
                                    
                                </div>
                            </div>
                            
                        </div>
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
                            @if ($val instanceof Htmlable) 
                                {!! $val->toHtml() !!}
                            @elseif (is_array($val) || is_object($val))
                                {!! serialize($val) !!}
                            @else
                                {!! $val !!}
                            @endif
                        @endslot
                    @endforeach
                    {{--  @slot('pagingFor')
                        {!! 'admin.Section-'. $section_url2 . '.Category-' . $category_url2 .  ' .ProductsPanel' !!}
                    @endslot  --}}
                @endcomponent
            </div>
        </div>
    @endif
        
</div>