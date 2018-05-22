
@php
 use \App\Utilities\Functions\Functions;
 
 $navbar2 = Functions::getBladedContent(isset($navbar)?$navbar:null);
@endphp
<!-- Derived and Inspired from Metronic Shop UI STYLE CUSTOMIZER -->

<!-- BEGIN USERS Scrolled Links Panel -->
<div class="users-links-panel hidden-sm">
    <div class="users-links-icons icon-users-links"></div>
    <div class="users-links-icons icon-users-links-close"></div>
    <div class="users-links">
        {{-- <p>THEME COLOR</p> --}}
        <ul class="inline">
            @if(isset($navbar))
            @php
                //dd($navbar);
            @endphp
            @foreach (unserialize($navbar) as $nav)
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
            @else
            <li class="color-red current color-default" data-style="red"></li>
            <li class="color-blue" data-style="blue"></li>
            <li class="color-green" data-style="green"></li>
            <li class="color-orange" data-style="orange"></li>
            <li class="color-gray" data-style="gray"></li>
            <li class="color-turquoise" data-style="turquoise"></li>
            @endif
        </ul>
    </div>
</div>
<!-- END USERS Scrolled Links Panel --> 



