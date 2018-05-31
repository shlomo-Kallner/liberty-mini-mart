
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
                    <li>
                        @if( isset($nav['type']) && ($nav['type'] == 'modal') )
                            <a href="#" data-toggle="modal" data-target="{{ $nav['target'] }}">
                        @else
                            <a href="{{ url($nav['url']) }}">
                        @endif
                            <i class="fa {{ $nav['icon'] }}"></i>
                        </a>        
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <!-- END USERS Scrolled Links Panel -->
@endif 



