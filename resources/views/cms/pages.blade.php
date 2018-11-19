
@php
    $testing = true;
    use \App\Utilities\Functions\Functions;
    use \Illuminate\Support\HtmlString;
    use Illuminate\Contracts\Support\Htmlable;

    $pages2 = Functions::getUnBladedContent($pages??[],[]);
    $paginator2 = Functions::getUnBladedContent($paginator??[],[]);

    $panelGroupId = 'pages-panel-group';
    //dd($pages2);
@endphp

<div class="panel-group" id="{{ $panelGroupId }}" role="tablist" aria-multiselectable="true">

    @forelse ($pages2 as $page)

        @php
            $tmpURL = explode('/', $page['url']);
            if (count($tmpURL) > 1) {
                $endUrlForId = collect($tmpURL)->last();
            } else {
                $endUrlForId = $tmpURL[0];
            }
            $panelId1 = 'headingPagePanel-of-' . $endUrlForId;
            $panelId2 = 'collapsePagePanel-of-' . $endUrlForId;
            $panelId3 = 'pageContentCollapsedDiv-of-' . $endUrlForId;
            $urls = [
                //'pages/{page}'
                'edit' => 'admin/' . $page['url'] . '/edit',
                //'create' => 'admin/pages/create',
                'delete' => 'admin/' . $page['url'] . '/delete',
                //'show' => 'admin/pages/{page}',
                //'index' => 'admin/pages/',
            ];
            $pageEditUrl = 'admin/' . $page['url'] . '/edit';
            $pageDeleteUrl = 'admin/' . $page['url'] . '/delete';
            
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
                        
                        @if (Functions::testVar($page['content']['img']))

                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 thumbnail">
                                <img src="{{ asset($page['content']['img']['img']) }}" class="img-responsive" alt="{{$page['content']['img']['alt']}}">
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
                                        <a class="btn btn-warning" href="{{ url($pageEditUrl) }}" role="button">Edit this Page</a>
                                        
                                        <a class="btn btn-danger" href="{{ url($pageDeleteUrl) }}" role="button">Delete this Page</a>
                                        
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
                                @component('lib.themewagon.article-sm')
                                    @foreach ($page['content']['article'] as $key => $item)
                                        @slot($key)
                                            @if ($key == 'img')
                                                {!! serialize($item) !!}
                                            @else
                                                {{ $item }}
                                            @endif
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
                            @if ($val instanceof Htmlable) 
                                {!! $val->toHtml() !!}
                            @elseif (is_array($val) || is_object($val))
                                {!! serialize($val) !!}
                            @else
                                {!! $val !!}
                            @endif
                        @endslot
                    @endforeach
                @endcomponent
            </div>
        </div>
    @endif

</div>
