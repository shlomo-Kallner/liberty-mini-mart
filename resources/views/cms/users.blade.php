

@php
    $testing = true;
    use \App\Utilities\Functions\Functions;

    $users2 = Functions::getUnBladedContent($users??'');
    $paginator2 = Functions::getUnBladedContent($paginator??'');

    $panelGroupId = 'users-panel-group';
@endphp

<div class="panel-group" id="{{ $panelGroupId }}" role="tablist" aria-multiselectable="true">

    @forelse ($users2 as $user)

        @php
            $panelId1 = 'headingSectionPanel-of-' . $section['url'];
            $panelId2 = 'collapseSectionPanel-of-' . $section['url'];
            $panelId3 = 'sectionContentCollapsedDiv-of-' . $section['url'];
            $urls = [
                //'user/{user}/'
                'edit' => 'admin/user/' . $user['id'] . '/edit',
                //'create' => 'admin/user/create',
                'delete' => 'admin/user/' . $user['id'] ,
                //'show' => 'store/user/' . $user['url']  ,
            ];
            $url_edit = 'admin/user/' . $user['id'] . '/edit';
            $url_delete = 'admin/user/' . $user['id'];
        
            $sectionPanelID = '';
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
                            <img src="{{ asset($section['img']) }}" class="img-responsive" alt="{{$section['imgAlt']}}">
                        </div>
                                
                        <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                            <div class="row">
                                <h4>{!! $section['title'] !!}</h4>
                            </div>
                                    
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="btn-group pull-left">
                                        <a class="btn btn-default" data-toggle="collapse" href="{{'#' . $panelId3}}" aria-expanded="false" aria-controls="{{ $panelId3 }}" role="button">Show User</a>
                                        <a class="btn btn-warning" href="{{ $url_edit }}" role="button">Edit this User</a>
                                        <button type="button" class="btn btn-danger" onclick="deleteSection('{{$url_delete}}')">Delete this User</button>
                                        
                                        {{-- <a class="btn btn-default" href="#" role="button"></a> --}}
                                        {{-- <a class="btn btn-default" href="#" role="button"></a> --}}
                                        {{-- <a class="btn btn-default" href="#" role="button"></a> --}}
                                            
                                    </div>
                                    
                                    <div class="btn-group pull-right">
                                        <button type="button" class="btn btn-default">{{ !$section['visible'] ? 'Show' : 'Hide' }}</button>
                                        <button type="button" class="btn btn-default">Move Up</button>
                                        <button type="button" class="btn btn-default">Move Down</button>
                                    </div>
                                    
                                </div>
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
                <h4>We Are Sorry! We have no Users!</h4>
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
                        {!! 'admin.UsersPanel' !!}
                    @endslot
                @endcomponent
            </div>
        </div>
    @endif

</div>




@section('js-extra')
    <script>
        
    </script>
@endsection