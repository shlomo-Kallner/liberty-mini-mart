
@php
    $testing = true;
    use \App\Utilities\Functions\Functions;

    $pages2 = Functions::getUnBladedContent($pages??[],[]);
    $paginator2 = Functions::getUnBladedContent($paginator??[],[]);

    $panelGroupId = 'pages-panel-group';
@endphp

<div class="panel-group" id="{{ $panelGroupId }}" role="tablist" aria-multiselectable="true">

    @forelse ($pages2 as $page)

        @php
            $panelId1 = 'headingPagePanel-of-' . $page['url'];
            $panelId2 = 'collapsePagePanel-of-' . $page['url'];
            $panelId3 = 'pageContentCollapsedDiv-of-' . $page['url'];
            $urls = [
                //'page/{page}'
                'edit' => 'admin/page/' . $page['url'] . '/edit',
                //'create' => 'admin/page/create',
                'delete' => 'admin/page/' . $page['url'] . '/delete',
                //'show' => $section['url']  ,
            ];
            $pageEditUrl = 'admin/page/' . $page['url'] . '/edit';
            $pageDeleteUrl = 'admin/page/' . $page['url'];
            //$newCategoryCreateUrl = 'admin/page/' . $page['url'] . '/category/create';
        
            //$sectionPanelID = '';
        @endphp

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="{{ $panelId1 }}">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="{{'#'. $panelGroupId}}" href="{{'#' . $panelId2}}" aria-expanded="true" aria-controls="{{$panelId2}}">
                        {{ $page['name'] }}
                    </a>
                </h4>
            </div>
            <div id="{{$panelId2}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{ $panelId1 }}">
                <div class="panel-body">
                    <div class="row">
                        
                        @if (Functions::testVar($page['img']))

                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 thumbnail">
                                <img src="{{ asset($page['img']) }}" class="img-responsive" alt="{{$page['imgAlt']}}">
                            </div>
                            
                        @else
                            {{-- a "no image for this __" to be created! --}}
                        @endif
                                
                        <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                            <div class="row">
                                <h4>{!! $page['title'] !!}</h4>
                            </div>
                                    
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="btn-group pull-left">
                                        <a class="btn btn-default" data-toggle="collapse" href="{{'#' . $panelId3}}" aria-expanded="false" aria-controls="{{ $panelId3 }}" role="button">Show Content</a>
                                        <a class="btn btn-warning" href="{{ $pageEditUrl }}" role="button">Edit this Page</a>
                                        <button type="button" class="btn btn-danger" onclick="deleteSection('{{$sectionDeleteUrl}}')">Delete this Page</button>
                                        
                                        {{-- <a class="btn btn-default" href="#" role="button"></a> --}}
                                        {{-- <a class="btn btn-default" href="#" role="button"></a> --}}
                                        {{-- <a class="btn btn-default" href="#" role="button"></a> --}}
                                            
                                    </div>
                                    
                                    <div class="btn-group pull-right">
                                        <button type="button" class="btn btn-default">{{ !$page['visible'] ? 'Show' : 'Hide' }}</button>
                                        <button type="button" class="btn btn-default">Move Up</button>
                                        <button type="button" class="btn btn-default">Move Down</button>
                                    </div>
                                    
                                </div>
                            </div>
            
                            <div class="row collapse" id="{{ $panelId3 }}">
                                @component('lib.themewagon.article')
                                    @foreach ($page as $key => $item)
                                        @slot($key)
                                            {{ $item }}
                                        @endslot
                                    @endforeach
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
                <h4>We Are Sorry! We have no Content Pages!</h4>
            </div>
        </div>
    @endforelse

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
                        {!! 'admin.PagesPanel' !!}
                    @endslot
                @endcomponent
            </div>
        </div>
    @endif

</div>
