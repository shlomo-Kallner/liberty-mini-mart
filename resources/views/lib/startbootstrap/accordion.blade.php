

@php
    $testing = true;
    use \App\Utilities\Functions\Functions;

    if ($testing) {
        $accordion2 = [
            [
                'panelClass' => 'panel-default',
                //'url' => '',
                'title' => 'panel 1',
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
                'title' => 'panel 2',
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
                'title' => 'panel 3',
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
        $accordionId2 = 'accordion';

    } else {
        $accordion2 = Functions::getUnBladedContent($accordion??'','');
        $accordionId2 = Functions::getBladedString($accordionId??'','accordion');
    }

@endphp

    <div class="panel-group" id="{{ $accordionId2 }}">
        @if (true)
            @foreach ($accordion2 as $item)
                <div class="panel {{ $item['panelClass'] }}">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="{{ '#' . $accordionId2 }}" href="{{ '#' . $accordionId2 . '-collapse-' . $loop->index }}"> {{ $item['title'] }}</a>
                        </h4>
                    </div>
                    <div id="{{ $accordionId2 . '-collapse-' . $loop->index }}" class="panel-collapse collapse in">
                        <div class="panel-body">
                            {!! $item['content'] !!}
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Collapsible Group Item #1</a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="panel-body">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
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
        @endif
    </div>