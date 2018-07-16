
@php
    $testing = true;
    use \App\Utilities\Functions\Functions;
    
    $categories2 = Functions::getUnBladedContent($categories??'');
    $section_url2 = Functions::getBladedString($section_url??'');
    $paginator2 = Functions::getUnBladedContent($paginator??'');

    $panelGroupId = 'categories-panel-group-of-' . $section_url2;
@endphp

<div class="panel-group" id="{{ $panelGroupId }}" role="tablist" aria-multiselectable="true">
    
    @forelse ($categories2 as $category)
        
        @php
            $panelId1 = 'headingCategoryPanel' . $category['url'] . '-of-' . $section_url2;
            $panelId2 = 'collapseCategoryPanel' . $category['url'] . '-of-' . $section_url2;
            $panelId3 = 'categoryContentCollapsedDiv' . $category['url'] . '-of-' . $section_url2;

            $categoryEditUrl = 'admin/section/' . $section_url2 . '/category/' . $category['url'] . '/edit';
            $categoryDeleteUrl = 'admin/section/' . $section_url2 . '/category/' . $category['url'];
            $newProductCreateUrl = 'admin/section/' . $section_url2 . '/category/' . $category['url'] . '/product/create';

            $urls = [
                //'section/{section}/category/{category}/product'
                'edit' => 'admin/section/' . $section_url2 . '/category/' . $category['url'] . '/edit',
                'delete' => 'admin/section/' . $section_url2 . '/category/' . $category['url'],
                'create' => 'admin/section/' . $section_url2 . '/category/' . $category['url'] . '/product/create',
                //'show' => 'store/section/' . $section_url2 . '/category/' . $category['url'] ,
            ];
        @endphp

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="{{ $panelId1 }}">
                <h3 class="panel-title">
                    <a role="button" data-toggle="collapse" 
                        data-parent="{{'#' . $panelGroupId}}" href="{{ '#' . $panelId2 }}" 
                        aria-expanded="true" aria-controls="{{ $panelId2 }}">
                        {!! $category['name'] !!}
                    </a>
                </h3>
            </div>
            <div id="{{ $panelId2 }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{ $panelId1 }}">
                <div class="panel-body">
                    <div class="row">

                        <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 thumbnail">
                            <img src="{{ asset($category['img']) }}" class="img-responsive" alt="{{$category['imgAlt']}}">
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
            
                            <div class="row collapse" id="{{ $panelId3 }}">
                                @component('cms.products')
                                    @slot('products')
                                        {!! serialize($category['products']) !!}
                                    @endslot
                                    @slot('category_url')
                                        {!! serialize($category['url']) !!}
                                    @endslot
                                    @slot('section_url')
                                        {{ $section_url }}
                                    @endslot
                                    @if (Functions::testVar($category['paginator']??''))
                                        @slot('paginator')
                                            {!! serialize($category['paginator']) !!}
                                        @endslot
                                    @endif
                                @endcomponent
                            </div>
                            
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
    @empty
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>We Are Sorry! There are no  Categories for this Section!</h4>
            </div>
        </div>
    @endforelse

    @if (Functions::testVar($paginator2) && Functions::testVar($categories2))
        <div class="panel panel-default">
            <div class="panel-body">
                @component('lib.themewagon.paginator')
                    @foreach ($paginator2 as $key => $val)
                        @slot($key)
                            {!! serialize($val) !!}
                        @endslot
                    @endforeach
                    @slot('pagingFor')
                        {!! 'admin.Section-'. $section_url2 .'.CategoriesPanel' !!}
                    @endslot
                @endcomponent
            </div>
        </div>
    @endif
    
</div>

@section('js-extra')
    @parent

    <script></script>
    
@endsection

