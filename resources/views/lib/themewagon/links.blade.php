
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
 $name2 = Functions::getBladedString($name??'','');
 $transform2 = Functions::getBladedString($transform??'','');

@endphp
<a href="{{ url($url2) }}"
    @if( Functions::testVar($type2) && ($type2 == 'modal') )
        data-toggle="modal" data-target="{{ $target2 }}"
    @endif
    @if (Functions::testVar($cssExtraClasses2))
        class="{{ $cssExtraClasses2 }}"
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
            
</a>

