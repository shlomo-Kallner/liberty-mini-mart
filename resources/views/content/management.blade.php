@extends('content.template')


@section('css-extra-fonts')
    @parent

    {{-- the font to be placed in a yield or in a child (extending) view.. --}}
    

@endsection


@section('css-preloaded-local')
    {{-- page local css --}}
    @parent

    <!-- MetisMenu CSS -->
    <link href="{{ asset('lib/startbootstrap/admin2/vendor/metisMenu/metisMenu.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('lib/startbootstrap/admin2/dist/css/sb-admin-2.css') }}" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="{{ asset('lib/startbootstrap/admin2/vendor/morrisjs/morris.css') }}" rel="stylesheet">

@endsection

@section('extra-navigation-content')
    @parent

    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-envelope fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-messages">
                <li>
                    <a href="#">
                        <div>
                            <strong>John Smith</strong>
                            <span class="pull-right text-muted">
                                <em>Yesterday</em>
                            </span>
                        </div>
                        <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <strong>John Smith</strong>
                            <span class="pull-right text-muted">
                                <em>Yesterday</em>
                            </span>
                        </div>
                        <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <strong>John Smith</strong>
                            <span class="pull-right text-muted">
                                <em>Yesterday</em>
                            </span>
                        </div>
                        <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a class="text-center" href="#">
                        <strong>Read All Messages</strong>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>
            </ul>
            <!-- /.dropdown-messages -->
        </li>
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-tasks fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-tasks">
                <li>
                    <a href="#">
                        <div>
                            <p>
                                <strong>Task 1</strong>
                                <span class="pull-right text-muted">40% Complete</span>
                            </p>
                            <div class="progress progress-striped active">
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                    <span class="sr-only">40% Complete (success)</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <p>
                                <strong>Task 2</strong>
                                <span class="pull-right text-muted">20% Complete</span>
                            </p>
                            <div class="progress progress-striped active">
                                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                    <span class="sr-only">20% Complete</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <p>
                                <strong>Task 3</strong>
                                <span class="pull-right text-muted">60% Complete</span>
                            </p>
                            <div class="progress progress-striped active">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                    <span class="sr-only">60% Complete (warning)</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <p>
                                <strong>Task 4</strong>
                                <span class="pull-right text-muted">80% Complete</span>
                            </p>
                            <div class="progress progress-striped active">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                    <span class="sr-only">80% Complete (danger)</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a class="text-center" href="#">
                        <strong>See All Tasks</strong>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>
            </ul>
            <!-- /.dropdown-tasks -->
        </li>
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-bell fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-alerts">
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-comment fa-fw"></i> New Comment
                            <span class="pull-right text-muted small">4 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                            <span class="pull-right text-muted small">12 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-envelope fa-fw"></i> Message Sent
                            <span class="pull-right text-muted small">4 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-tasks fa-fw"></i> New Task
                            <span class="pull-right text-muted small">4 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-upload fa-fw"></i> Server Rebooted
                            <span class="pull-right text-muted small">4 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a class="text-center" href="#">
                        <strong>See All Alerts</strong>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>
            </ul>
            <!-- /.dropdown-alerts -->
        </li>
        <!-- /.dropdown -->
        @if (false)
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                    </li>
                    <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                    </li>
                    <li class="divider"></li>
                    <li><a href="{{ url('lib/startbootstrap/admin2/login.html') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        @endif
        
    </ul>
    <!-- /.navbar-top-links -->

    @component('lib.startbootstrap.sidebar_menu')
        
    @endcomponent

@endsection

@section('main-content')

    @php
    $testing = true;
    use \App\Utilities\Functions\Functions,
        \App\Page;

    if ($testing) {
        $quickLinks2 = [
            [
                'containerCss' => 'col-lg-3 col-md-6',
                'panelClass' => 'panel-primary',
                'headerIcon' => 'fa-comments',
                'numContent' => '26',
                'contentTitle' => 'New Comments!',
                'panelLinkUrl' => '#'
            ],
            [
                'containerCss' => 'col-lg-3 col-md-6',
                'panelClass' => 'panel-green',
                'headerIcon' => 'fa-tasks',
                'numContent' => '12',
                'contentTitle' => 'New Tasks!',
                'panelLinkUrl' => '#'
            ],
            [
                'containerCss' => 'col-lg-3 col-md-6',
                'panelClass' => 'panel-yellow',
                'headerIcon' => 'fa-shopping-cart',
                'numContent' => '124',
                'contentTitle' => 'New Orders!',
                'panelLinkUrl' => '#'
            ],
            [
                'containerCss' => 'col-lg-3 col-md-6',
                'panelClass' => 'panel-red',
                'headerIcon' => 'fa-support',
                'numContent' => '13',
                'contentTitle' => 'Support Tickets!',
                'panelLinkUrl' => '#'
            ]
        ];
        $tabs2 = [
            [
                'name' => 'home', // also the HTML 'id' attribute value
                'title' => '<h4>Home Tab</h4>',
                'content' => '<p>
                                Lorem ipsum dolor sit amet, 
                                consectetur adipisicing elit, 
                                sed do eiusmod tempor incididunt 
                                ut labore et dolore magna aliqua. 
                                Ut enim ad minim veniam, quis 
                                nostrud exercitation ullamco 
                                laboris nisi ut aliquip ex ea 
                                commodo consequat. Duis aute irure 
                                dolor in reprehenderit in voluptate 
                                velit esse cillum dolore eu fugiat 
                                nulla pariatur. Excepteur sint 
                                occaecat cupidatat non proident, 
                                sunt in culpa qui officia deserunt 
                                mollit anim id est laborum.
                            </p>'
            ],
            [
                'name' => 'profile', // also the HTML 'id' attribute value
                'title' => '<h4>Profile Tab</h4>',
                'content' => '<p>
                        Lorem ipsum dolor sit amet, 
                        consectetur adipisicing elit, 
                        sed do eiusmod tempor incididunt 
                        ut labore et dolore magna aliqua. 
                        Ut enim ad minim veniam, quis 
                        nostrud exercitation ullamco 
                        laboris nisi ut aliquip ex ea 
                        commodo consequat. Duis aute irure 
                        dolor in reprehenderit in voluptate 
                        velit esse cillum dolore eu fugiat 
                        nulla pariatur. Excepteur sint 
                        occaecat cupidatat non proident, 
                        sunt in culpa qui officia deserunt 
                        mollit anim id est laborum.
                    </p>'
            ],
            [
                'name' => 'messages', // also the HTML 'id' attribute value
                'title' => '<h4>Messages Tab</h4>',
                'content' => '<p>
                        Lorem ipsum dolor sit amet, 
                        consectetur adipisicing elit, 
                        sed do eiusmod tempor incididunt 
                        ut labore et dolore magna aliqua. 
                        Ut enim ad minim veniam, quis 
                        nostrud exercitation ullamco 
                        laboris nisi ut aliquip ex ea 
                        commodo consequat. Duis aute irure 
                        dolor in reprehenderit in voluptate 
                        velit esse cillum dolore eu fugiat 
                        nulla pariatur. Excepteur sint 
                        occaecat cupidatat non proident, 
                        sunt in culpa qui officia deserunt 
                        mollit anim id est laborum.
                    </p>'
            ],
            [
                'name' => 'settings', // also the HTML 'id' attribute value
                'title' => '<h4>Settings Tab</h4>',
                'content' => '<p>
                        Lorem ipsum dolor sit amet, 
                        consectetur adipisicing elit, 
                        sed do eiusmod tempor incididunt 
                        ut labore et dolore magna aliqua. 
                        Ut enim ad minim veniam, quis 
                        nostrud exercitation ullamco 
                        laboris nisi ut aliquip ex ea 
                        commodo consequat. Duis aute irure 
                        dolor in reprehenderit in voluptate 
                        velit esse cillum dolore eu fugiat 
                        nulla pariatur. Excepteur sint 
                        occaecat cupidatat non proident, 
                        sunt in culpa qui officia deserunt 
                        mollit anim id est laborum.
                    </p>'
            ]
        ];
        $sidebarMenu2 = serialize($sidebar??Page::getSidebar(true));
    } else {
        $sidebarMenu2 = Functions::getContent($sidebarMenu??'');
    }
        
    @endphp

    <div id="page-wrapper">

        {{-- an unneeded title row.. --}}
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Dashboard</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->

        {{-- BEGIN: quick links.. --}}
        <div class="row">
            
            @foreach ($quickLinks2 as $link)
                @component('lib.startbootstrap.quick_link_panel')
                    @foreach ($link as $key => $item)
                        @slot($key)
                            {!! $item !!}
                        @endslot
                    @endforeach
                @endcomponent
            @endforeach

        </div>
        <!-- /.row -->
        {{-- END: quick links.. --}}

        @if (false)
            
            <div class="row">
                @component('lib.startbootstrap.tab_panel')
                    @slot('containerClass')
                        {!! 'col-lg-12' !!}
                    @endslot
                    @slot('panelClass')
                        {!! 'panel-default' !!}
                    @endslot
                    @slot('panelHeader')
                        {!! 'Basic Tabs' !!}
                    @endslot
                    @slot('panelFooter')
                        {!! 'Panel Footer' !!}
                    @endslot
                    @slot('activeTab')
                        {!! 'home' !!}
                    @endslot
                    @slot('panelTabs')
                        {!! serialize($tabs2) !!}
                    @endslot
                @endcomponent
            </div>
            <div class="row">
                <div class="col-lg-8">
                    @php
                        $accordion = [
                            [
                                'panelClass' => 'panel-default',
                                'title' => 'tabs panel',
                                'content' => 'Lorem ipsum dolor sit amet, 
                                            consectetur adipisicing elit, 
                                            sed do eiusmod tempor incididunt 
                                            ut labore et dolore magna aliqua. 
                                            Ut enim ad minim veniam, quis 
                                            nostrud exercitation ullamco 
                                            laboris nisi ut aliquip ex ea 
                                            commodo consequat. Duis aute irure 
                                            dolor in reprehenderit in voluptate 
                                            velit esse cillum dolore eu fugiat 
                                            nulla pariatur. Excepteur sint 
                                            occaecat cupidatat non proident, 
                                            sunt in culpa qui officia deserunt 
                                            mollit anim id est laborum.'
                            ],
                            [
                                'panelClass' => 'panel-default',
                                'title' => 'panel 4',
                                'content' => 'Lorem ipsum dolor sit amet, 
                                            consectetur adipisicing elit, 
                                            sed do eiusmod tempor incididunt 
                                            ut labore et dolore magna aliqua. 
                                            Ut enim ad minim veniam, quis 
                                            nostrud exercitation ullamco 
                                            laboris nisi ut aliquip ex ea 
                                            commodo consequat. Duis aute irure 
                                            dolor in reprehenderit in voluptate 
                                            velit esse cillum dolore eu fugiat 
                                            nulla pariatur. Excepteur sint 
                                            occaecat cupidatat non proident, 
                                            sunt in culpa qui officia deserunt 
                                            mollit anim id est laborum.'
                            ]
                        ];
                    @endphp
                    @component('lib.startbootstrap.accordion')
                        @slot('accordion')
                            {!! serialize($accordion) !!}
                        @endslot
                    @endcomponent
                </div>
            </div>
    
        @endif

        <div class="row">
            
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                @component('lib.startbootstrap.site_management')
                    
                @endcomponent
            </div>
            
        </div>

        @if (false)
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Collapsible Accordion Panel Group
                        </div>
                        <!-- .panel-heading -->
                        <div class="panel-body">
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Collapsible Group Item #1</a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body  table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>First Name</th>
                                                        <th>Last Name</th>
                                                        <th>Username</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Mark</td>
                                                        <td>Otto</td>
                                                        <td>@mdo</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Jacob</td>
                                                        <td>Thornton</td>
                                                        <td>@fat</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Larry</td>
                                                        <td>the Bird</td>
                                                        <td>@twitter</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Collapsible Group Item #2</a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Collapsible Group Item #3</a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- .panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
    
            <div class="row">

                <div class="col-lg-8">
                    @if (false)
                        @component('lib.startbootstrap.area_chart_panel')
                            
                        @endcomponent
                    @endif
                    @if (false)
                        @component('lib.startbootstrap.barchart_panel')
                            
                        @endcomponent
                    @endif
                    @if (true)
                        @component('lib.startbootstrap.timeline_panel')
                            
                        @endcomponent
                    @endif
                </div>
                <!-- /.col-lg-8 -->

                <div class="col-lg-4">
                    
                    @if (true)
                        @component('lib.startbootstrap.notifications_panel')
                            
                        @endcomponent
                    @endif
    
                    @if (false)
                        @component('lib.startbootstrap.donut_chart_panel')
                            
                        @endcomponent
                    @endif
    
                    @if (false)
                        @component('lib.startbootstrap.chat_panel')
                            
                        @endcomponent
                    @endif
                 
                </div>
                <!-- /.col-lg-4 -->
                
            </div>
            <!-- /.row -->
        @endif

    </div>
    <!-- /#page-wrapper -->

@endsection

@section('js-defered')
    @parent

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{ asset('lib/startbootstrap/admin2/vendor/metisMenu/metisMenu.min.js') }}"></script>

    <!-- Morris Charts JavaScript -->
    <script src="{{ asset('lib/startbootstrap/admin2/vendor/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('lib/startbootstrap/admin2/vendor/morrisjs/morris.min.js') }}"></script>
    {{-- 
        temporarily removing this script element so that I can remove or otherwise 
        initialize the Morris.js components elsewise..
        <script src="{{ asset('lib/startbootstrap/admin2/data/morris-data.js') }}"></script> 
    --}}
    

    <!-- Custom Theme JavaScript -->
    <script src="{{ asset('lib/startbootstrap/admin2/dist/js/sb-admin-2.js') }}"></script>

@endsection

