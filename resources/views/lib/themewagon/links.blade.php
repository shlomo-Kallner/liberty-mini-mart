
@php
   /*
    * A template for a 'normal-link' or a 'modal-link' @LINK!@ tag
    * .. and nothing else!
    * 
    * $templateItem = [
    'icon' => '', // the Font Awesome 4 icon class without the 'fa'.
    'name' => '', // the name to fill in the Link.
    'url' => '', // the URL of the link.
    'type' => 'url', // 'url' for a url link, 'modal' for a modal button link..  
    'target' => '', // the data-target attribute's data value (of a modal)
    'transform' => '', // Bootstrap 3 text-transform css class.
    'cssExtraClasses' => '' // extra CSS classes for the a tag..
    ];
    * 
    */
 
 $testing = false;
 use \App\Utilities\Functions\Functions;

 $type2 = Functions::getBladedString($type??'','url');

 $target2 = Functions::getBladedString($target??'','');
 $cssExtraClasses2 = Functions::getBladedString($cssExtraClasses??'','');
 $url2 = Functions::getBladedString($url??'','#');
 $icon2 = Functions::getBladedString($icon??'','');
 $iconAfter2 = Functions::getBladedString($iconAfter??'','');
 $name2 = Functions::getBladedString($name??'','');
 $transform2 = Functions::getBladedString($transform??'','');
 $controls2 = Functions::getBladedString($controls??'','');

 if (Functions::testVar($type2) && ($type2 == 'modal')) {
    $toggle2 = Functions::getBladedString($toggle??'modal','modal');
 } elseif (Functions::testVar($type2) && ($type2 == 'dropdown')) {
    $toggle2 = Functions::getBladedString($toggle??'dropdown','dropdown');
 } elseif(Functions::testVar($type2) && ($type2 == 'collapse')) {
    $toggle2 = Functions::getBladedString($toggle??'collapse','collapse');
 } else {
    $toggle2 = Functions::getBladedString($toggle??'','');
 }

 $role2 = Functions::getBladedString($role??'','');
 

@endphp
<a 
    @if (Functions::testVar($url2))
        href="{{ url($url2) }}"
    @endif

    @if(Functions::testVar($toggle2))
        data-toggle="{{ $toggle2 }}"
    @endif

    @if (Functions::testVar($target2))
        data-target="{{ $target2 }}"
    @endif

    @if (Functions::testVar($cssExtraClasses2))
        class="{{ $cssExtraClasses2 }}"
    @endif

    @if (Functions::testVar($role2))
        role="{{ $role2 }}"
    @endif

    @if (Functions::testVar($controls2))
        aria-controls="{{ $controls2 }}"
    @endif

    @if (Functions::testVar($type2) && ($type2 == 'collapse'))
        aria-expanded="false"
    @endif

    >


        @if( Functions::testVar($icon2) && (mb_strlen($icon2) !== 0) )
            @if ( !Functions::testVar($name2) || (mb_strlen($name2) === 0)  )
            <i class="fa {{ $icon2 }}"></i>    
            @else
            <i class="fa {{ $icon2 }}" aria-hidden="true"></i>
            @endif
        @endif

        @if ( Functions::testVar($name2) && (mb_strlen($name2) !== 0)  ) 
            @if(mb_strlen($transform2) !== 0)
                <span class="hidden-xs {{ $transform2 }}">{!! $name2 !!}</span> 
            @else 
                {!! $name2 !!}
            @endif 
        @endif

        @if( Functions::testVar($iconAfter2) && (mb_strlen($iconAfter2) !== 0) )
            <i class="fa {{ $iconAfter2 }}"></i>
        @endif
            
</a>

