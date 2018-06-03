

{{-- NOTE: this is a ADVANCED-TASK COMPONENT! 
    Not to be done until the main project is completed! --}}
@php

$testing = false;
use \App\Utilities\Functions\Functions,
    \App\Utilities\IterationStack\IterationStack,
    \App\Utilities\IterationStack\IterationFrame;

$listCSS2 = Functions::getBladedString($listCSS??'','');
 
$type2 = Functions::getBladedString($type??'','dropdown');
$target2 = Functions::getBladedString($target??'','#');
$cssExtraClasses2 = Functions::getBladedString($cssExtraClasses??'','');
$url2 = Functions::getBladedString($url??'','javascript:void(0);');
$icon2 = Functions::getBladedString($icon??'','');
$name2 = Functions::getBladedString($name??'','');
$transform2 = Functions::getBladedString($transform??'','');
$submenus2 = Functions::getUnBladedContent($submenus??'','');

@endphp

{{-- begin dropdown menu top-level link --}}
<li class="dropdown {!! $listCSS2 !!}">
    <a class="dropdown-toggle {{ $cssExtraClasses2 }}" data-toggle="dropdown" data-target="{{ $target2 }}" href="{{ url($url2) }}" role="button">
        
            @if( Functions::testVar($icon2) && (mb_strlen($icon2) !== 0) )
            @if ( !Functions::testVar($name2) || (mb_strlen($name2) === 0)  )
            <i class="fa {{ $icon2 }}"></i>    
            @else
            <i class="fa {{ $icon2 }}" aria-hidden="true"></i>
            @endif
        @endif

        @if ( Functions::testVar($name2) && (mb_strlen($name2) !== 0)  ) 
            @if(mb_strlen($transform2) !== 0)
                <span class="hidden-xs {{ $transform2 }}">{{ $name2 }}</span> 
            @else 
                {{ $name2 }} 
            @endif 
        @endif

    </a>

    <!-- BEGIN DROPDOWN MENU -->
    <ul class="dropdown-menu" style="display:none;">
        
        @foreach($submenus2 as $nav)

            @if( $nav['type'] == 'url' || $nav['type'] == 'modal' )

                @component('lib.themewagon.menu_links')
                    @foreach ($nav as $key => $value)

                        @slot($key)
                            {{ $value }}
                        @endslot
                            
                    @endforeach
                @endcomponent

            @elseif($nav['type'] == 'dropdown-submenu')
                {{-- Begin submenu.. --}}
                
                {{-- DROPDOWN-SUBMENUS ARE A WISHLIST-TASK COMPONENT -> NOT IMPLEMENTED YET! --}}
                
                {{-- End submenu.. --}}

            @endif
        @endforeach

    </ul>
    <!-- END DROPDOWN MENU -->
</li>
{{-- end dropdown menu top-level link --}}

