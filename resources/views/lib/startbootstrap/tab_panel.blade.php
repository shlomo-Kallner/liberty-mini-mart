
@php
    $testing = false;
    use \App\Utilities\Functions\Functions;

    if ($testing) {
        $containerClass2 = 'col-lg-12';
        $panelClass2 = 'panel-default';
        $panelHeader2 = 'Basic Tabs';
        $panelTabs2 = [
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
        $activeTab2 = 'home';
        $panelFooter2 = 'Panel Footer';
    } else {
        
        $containerClass2 = Functions::getBladedString($containerClass??'');
        $panelClass2 = Functions::getBladedString($panelClass??'');
        $panelHeader2 = Functions::getBladedString($panelHeader??'');
        $panelFooter2 = Functions::getBladedString($panelFooter??'');
        $activeTab2 = Functions::getBladedString($activeTab??'');
        $panelTabs2 = Functions::getUnBladedContent($panelTabs??'');
    }
    
@endphp

@component('lib.startbootstrap.basic_panel')
    @slot('containerClass')
        {!! $containerClass2 !!}
    @endslot
    @slot('panelClass')
        {!! $panelClass2 !!}
    @endslot
    @slot('panelHeader')
        {!! $panelHeader2 !!}
    @endslot
    @slot('panelFooter')
        {!! $panelFooter2 !!}
    @endslot
    @slot('panelContent')
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            @foreach ($panelTabs2 as $tab)
                @if ($tab['name'] === $activeTab2)
                    <li class="active">
                @else
                    <li>
                @endif
                        <a href="{{ '#'. $tab['name'] }}" data-toggle="tab">
                            {{ title_case($tab['name']) }}
                        </a>
                    </li>
            @endforeach
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            @foreach ($panelTabs2 as $tab)
                @if ($tab['name'] === $activeTab2)
                    <div class="tab-pane fade in active" id="{{ $tab['name'] }}">
                @else
                    <div class="tab-pane fade" id="{{ $tab['name'] }}">
                @endif
                        {!! $tab['title'] !!}
                        {!! $tab['content'] !!}
                    </div>
            @endforeach
        </div>
    @endslot                 
@endcomponent
    