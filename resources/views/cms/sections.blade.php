@extends('content.base')

@section('main-content')
    @parent

        
    @php
        $testing = true;
        use \App\Utilities\Functions\Functions;
        use \Illuminate\Support\HtmlString;
        use Illuminate\Contracts\Support\Htmlable;

        $sections2 = Functions::getUnBladedContent($sections??[],[]);
        $paginator2 = Functions::getUnBladedContent($paginator??[],[]);

        $panelGroupId = 'sections-panel-group';
        $useColapse = false;

        //dd($sections2, $paginator2);
    @endphp

    @if ($useColapse)
        <div class="panel-group" id="{{ $panelGroupId }}" role="tablist" aria-multiselectable="true">

            @forelse ($sections2 as $section)

                @php
                    $panelId1 = 'headingSectionPanel-of-' . $section['url'];
                    $panelId2 = 'collapseSectionPanel-of-' . $section['url'];
                    $panelId3 = 'sectionContentCollapsedDiv-of-' . $section['url'];
                    $panelId4 = 'sectionImagesCollapsedDiv-of-' . $section['url'];
                    $urls = [
                        //'section/{section}/category/{category}/product'
                        'edit' => 'admin/section/' . $section['url'] . '/edit',
                        //'create' => 'admin/section/create',
                        'delete' => 'admin/section/' . $section['url'] ,
                        //'show' => 'store/section/' . $section['url']  ,
                    ];
                    $sectionEditUrl = 'admin/section/' . $section['url'] . '/edit';
                    $sectionDeleteUrl = 'admin/section/' . $section['url'];
                    $newCategoryCreateUrl = 'admin/section/' . $section['url'] . '/category/create';
                    $img = $section['img'];
                
                    //$sectionPanelID = '';
                @endphp

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="{{ $panelId1 }}">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="{{'#'. $panelGroupId}}" href="{{'#' . $panelId2}}" aria-expanded="true" aria-controls="{{$panelId2}}">
                                {{ $section['name'] }}
                            </a>
                        </h4>
                    </div>
                    <div id="{{$panelId2}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{ $panelId1 }}">
                        <div class="panel-body">
                            <div class="row">
                                
                                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 thumbnail">
                                    <img src="{{ asset($img['img']) }}" class="img-responsive" alt="{{$img['alt']}}">
                                </div>
                                        
                                <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                    <div class="row">
                                        <h4>{!! $section['title'] !!}</h4>
                                    </div>
                                            
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="btn-group pull-left">
                                                <a class="btn btn-default" data-toggle="collapse" href="{{'#' . $panelId3}}" aria-expanded="false" aria-controls="{{ $panelId3 }}" role="button">Show Categories</a>
                                                <a class="btn btn-primary" href="{{ $newCategoryCreateUrl }}" role="button">Create a New Category</a>
                                                <a class="btn btn-warning" href="{{ $sectionEditUrl }}" role="button">Edit this Section</a>
                                                <button type="button" class="btn btn-danger" onclick="deleteSection('{{$sectionDeleteUrl}}')">Delete this Section</button>
                                                
                                                {{-- <a class="btn btn-default" href="#" role="button"></a> --}}
                                                {{-- <a class="btn btn-default" href="#" role="button"></a> --}}
                                                {{-- <a class="btn btn-default" href="#" role="button"></a> --}}
                                                    
                                            </div>
                                            
                                            <div class="btn-group pull-right">
                                                @if (Functions::testVar($section['visible']??null))
                                                    <button type="button" class="btn btn-default">{{ !$section['visible'] ? 'Show' : 'Hide' }}</button>
                                                @endif
                                                <button type="button" class="btn btn-default">Move Up</button>
                                                <button type="button" class="btn btn-default">Move Down</button>
                                            </div>
                                            
                                        </div>
                                    </div>

                                    <div class="row collapse" id="{{ $panelId4 }}">
                                        @component('inc.carousel')
                                            @slot('images')
                                                {!! serialize($section['otherImages']??'') !!}
                                            @endslot
                                            @slot('carouselID')
                                                {{ $panelId4 }}
                                            @endslot
                                        @endcomponent
                                    </div>
                    
                                    <div class="row collapse" id="{{ $panelId3 }}">
                                        @component('cms.categories')
                                            @slot('categories')
                                                {!! serialize($section['categories']) !!}
                                            @endslot
                                            @slot('section_url')
                                                {!! serialize($section['url']) !!}
                                            @endslot
                                            @if (Functions::testVar($section['paginator']??''))
                                                @slot('paginator')
                                                    {!! serialize($section['paginator']) !!}
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
                        <h4>We Are Sorry! We have no Sections!</h4>
                    </div>
                </div>
            @endforelse
            
            @if (Functions::testVar($paginator2))
                <div class="panel panel-default">
                    <div class="panel-body">
                        @component('lib.themewagon.paginator')
                            @foreach ($paginator2 as $key => $val)
                                @slot($key)
                                    {!! Functions::toBladableContent($val) !!}
                                @endslot
                            @endforeach
                            {{--  @slot('pagingFor')
                                {!! 'admin.SectionsPanel' !!}
                            @endslot  --}}
                        @endcomponent
                    </div>
                </div>
            @endif
            
        </div>
    @else

        <div class="row margin-bottom-40">
                    
            @component('lib.themewagon.sidebar')
                @slot('menu')
                    {!! Functions::toBladableContent($sidebar2) !!}
                @endslot
                @slot('sidebarClasses')
                    {!! 'col-md-3 col-sm-5' !!}
                @endslot
            @endcomponent

            <div class="col-md-9 col-sm-7">
                @component('lib.themewagon.article')
                    @foreach ($article2 as $key => $item)
                        @slot($key)
                            {!! Functions::toBladableContent($item) !!}
                        @endslot
                    @endforeach
                @endcomponent

                @component('lib.themewagon.content_list')
                    @slot('sorting')
                        {!! $sorting2 !!}
                    @endslot
                    @slot('items')
                        {!! $items2 !!}
                    @endslot
                    @slot('pageNumber')
                        {{ $pageNumber2 }}
                    @endslot
                    @slot('itemsPerPage')
                        {{ $itemsPerPage2 }}
                    @endslot
                    @slot('currency')
                        {{ $currency2 }}
                    @endslot
                @endcomponent
                
            </div>

        </div>

    @endif  
@endsection

@section('js-main')
    @parent
    @php
        // dd(asset('js/admin.js'), asset(mix('js/admin.js')));
    @endphp
    @if ($useVueEl)
        <script src="{{ asset(mix('js/admin_vue.js')) }}" type="text/javascript"></script>
    @else
        <script src="{{ asset(mix('js/admin_blade.js')) }}" type="text/javascript"></script>
    @endif
@endsection
