
@php
    $testing = true;
    use \App\Utilities\Functions\Functions;
    
    $categories2 = Functions::getUnBladedContent($categories??'');
    $section2 = Functions::getUnBladedContent($section??'');
    $paginator2 = Functions::getUnBladedContent($paginator??'');

    $panelGroupId = 'categories-panel-group-of-' . $section2['url'];
@endphp

<div class="panel-group" id="{{ $panelGroupId }}" role="tablist" aria-multiselectable="true">
    @foreach ($categories2 as $category)

        @php
            $panelId1 = 'headingCategoryPanel' . $category['url'] . '-of-' . $section2['url'];
            $panelId2 = 'collapseCategoryPanel' . $category['url'] . '-of-' . $section2['url'];
            $panelId3 = 'categoryContentCollapsedDiv' . $category['url'] . '-of-' . $section2['url'];
            $urls = [
                //'section/{section}/category/{category}/product'
                'edit' => 'admin/section/' . $section2['url'] . '/category/' . $category['url'] . '/edit',
                //'create' => 'admin/section/' . $section2['url'] . '/category/' . $category['url'] . '/create',
                'delete' => 'admin/section/' . $section2['url'] . '/category/' . $category['url'],
                //'show' => 'store/section/' . $section2['url'] . '/category/' . $category['url'] ,
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
                                        <a class="btn btn-default" data-toggle="collapse" href="{{'#' . $panelId3}}" aria-expanded="false" aria-controls="{{ $panelId3 }}" role="button">Show Content</a>
                                        <a class="btn btn-warning" href="{{ $urls['edit'] }}" role="button">Edit</a>
                                        <a class="btn btn-danger" href="#" role="button">Delete</a>
                                        
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
            
                            <div class="collapse" id="{{ $panelId3 }}">
                                @component('cms.products')
                                    @slot('products')
                                        {!! serialize($category['products']) !!}
                                    @endslot
                                    @slot('category')
                                        {!! serialize($category) !!}
                                    @endslot
                                    @slot('section')
                                        {!! $section !!}
                                    @endslot
                                    @if (Functions::testVar($category['paginator']))
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
            
    @endforeach
    
    @if (Functions::testVar($paginator2))
        <div class="panel panel-default">
            <div class="panel-body">
                @component('lib.themewagon.paginator')
                    @foreach ($paginator2 as $key => $val)
                        @slot($key)
                            {!! serialize($val) !!}
                        @endslot
                    @endforeach
                    @slot('pagingFor')
                        {!! 'admin.Section-'.$section2['url'].'.CategoriesPanel' !!}
                    @endslot
                @endcomponent
            </div>
        </div>
    @endif
    
    
</div>

