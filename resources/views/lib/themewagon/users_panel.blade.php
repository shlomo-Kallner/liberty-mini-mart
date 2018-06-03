
@php
    use \App\Utilities\Functions\Functions;

    $navbar2 = Functions::getUnBladedContent($navbar??'','');

@endphp

@if(Functions::testVar($navbar2))
    @php
        //dd($navbar2);
    @endphp
    <!-- Derived and Inspired from Metronic Shop UI STYLE CUSTOMIZER -->
    <!-- BEGIN USERS Scrolled Links Panel -->
    <div class="users-links-panel hidden-sm">
        <div class="users-links-icons icon-users-links"></div>
        <div class="users-links-icons icon-users-links-close"></div>
        <div class="users-links">
            {{-- <p>THEME COLOR</p> --}}
            <ul class="inline">
                @foreach ($navbar2 as $nav)

                    @if (true)
                    @php
                        // remove/erase the 'name' property... 
                        // as we don't use it here..
                        // and so we prevent it from propagating 
                        //  to the links component..
                        unset($nav['name']);
                        //dd($nav);
                    @endphp
                        @component('lib.themewagon.menu_links')
                            @foreach ($nav as $key => $value)

                                @slot($key)
                                    {{ $value }}
                                @endslot
                                
                            @endforeach
                        @endcomponent
                    @else
                        <li>
                            @if( isset($nav['type']) && ($nav['type'] == 'modal') )
                                <a href="#" data-toggle="modal" data-target="{{ $nav['target'] }}">
                            @else
                                <a href="{{ url($nav['url']) }}">
                            @endif
                                <i class="fa {{ $nav['icon'] }}"></i>
                            </a>        
                        </li>
                    @endif
                    
                @endforeach
            </ul>
        </div>
    </div>
    <!-- END USERS Scrolled Links Panel -->
@endif 



