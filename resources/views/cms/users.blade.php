

@php
    $testing = true;
    use \App\Utilities\Functions\Functions;
    use \Illuminate\Support\HtmlString;
    use Illuminate\Contracts\Support\Htmlable;

    $users2 = Functions::getUnBladedContent($users??[],[]);
    $paginator2 = Functions::getUnBladedContent($paginator??[],[]);

    //dd($paginator2);

    $panelGroupId = 'users-panel-group';
@endphp

<div class="panel-group" id="{{ $panelGroupId }}" role="tablist" aria-multiselectable="true">

    @forelse ($users2 as $user)

        @php
            $uid = false ? Functions::int2url_encode($user['uuid']) : $user['uuid'];
            $panelId1 = 'headingSectionPanel-of-User-' . $uid;
            $panelId2 = 'collapseSectionPanel-of-User-' . $uid;
            $panelId3 = 'sectionContentCollapsedDiv-of-User-' . $uid;
            $urls = [
                //'user/{user}/'
                'edit' => 'admin/user/' . $uid . '/edit',
                //'create' => 'admin/user/create',
                'delete' => 'admin/user/' . $uid ,
                //'show' => 'store/user/' . $user['url']  ,
            ];
            $url_edit = 'admin/user/' . $uid . '/edit';
            $url_delete = 'admin/user/' . $uid . '/delete';

        @endphp

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="{{ $panelId1 }}">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="{{'#'. $panelGroupId}}" href="{{'#' . $panelId2}}" aria-expanded="true" aria-controls="{{$panelId2}}">
                        {{ $user['name'] }}
                    </a>
                </h4>
            </div>
            <div id="{{$panelId2}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{ $panelId1 }}">
                <div class="panel-body">
                    <div class="row">
                        
                        <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 thumbnail">
                            <img src="{{ asset($user['img']['img']) }}" class="img-responsive" alt="{{$user['img']['alt']}}">
                        </div>
                                
                        <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                            <div class="row">
                                <h4>{!! 'NAME: ' . $user['name'] !!}</h4>
                                <hr>
                                <h4>{!! 'Email: ' . $user['email'] !!}</h4>
                            </div>
                                    
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="btn-group pull-left">
                                        <a class="btn btn-default" data-toggle="collapse" href="{{'#' . $panelId3}}" aria-expanded="false" aria-controls="{{ $panelId3 }}" role="button">Show User</a>
                                        <a class="btn btn-warning" href="{{ $url_edit }}" role="button">Edit this User</a>
                                        <a class="btn btn-danger" href="{{ $url_delete }}" role="button">Delete this User</a>
                                        
                                        {{-- <a class="btn btn-default" href="#" role="button"></a> --}}
                                        {{-- <a class="btn btn-default" href="#" role="button"></a> --}}
                                        {{-- <a class="btn btn-default" href="#" role="button"></a> --}}
                                            
                                    </div>
                                    
                                    
                                    
                                </div>
                            </div>
            
                            <div class="row collapse" id="{{ $panelId3 }}">
                                @component('inc.carousel')
                                    @slot('images')
                                        {!! serialize($user['otherImages']) !!}
                                    @endslot
                                    @slot('carouselID')
                                        {{ $panelId3 }}
                                    @endslot
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
                            @if ($val instanceof Htmlable) 
                                {!! $val->toHtml() !!}
                            @elseif (is_array($val) || is_object($val))
                                {!! serialize($val) !!}
                            @else
                                {!! $val !!}
                            @endif
                        @endslot
                    @endforeach
                    {{-- 
                        @slot('pagingFor')
                            {!! 'admin.UsersPanel' !!}
                        @endslot 
                    --}}
                @endcomponent
            </div>
        </div>
    @endif

</div>




@section('js-extra')
    <script>
        
    </script>
@endsection