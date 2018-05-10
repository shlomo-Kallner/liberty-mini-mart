
<?php
/*
 * A template for a 'normal-link' or a 'modal-link' menu item .. and nothing else!
 * 
 * $templateItem = [
  'icon' => '', // the Font Awesome 4 icon class without the 'fa'.
  'name' => '', // the name to fill in the Link.
  'url' => '', // the URL of the link.
  'type' => 'url', // 'url' for a url link, 'modal' for a modal button link..  
  'target' => '', // the data-target attribute's data value (of a modal)
  'transform' => '', // Bootstrap 3 text-transform css class.
  ];
 * 
 */
?>

<li>
    
@if( isset($type) && ($type == 'modal') )
    <a href="#" data-toggle="modal" data-target="{{ $target }}">
@else
    <a href="{{ url($url) }}">
@endif

            @if( isset($icon) && (mb_strlen($icon) !== 0) )
                @if ( !isset($name) || (mb_strlen($name) === 0)  )
                <i class="fa {{ $icon }}"></i>    
                @else
                <i class="fa {{ $icon }}" aria-hidden="true"></i>
                @endif
            @endif

            @if ( isset($name) && (mb_strlen($name) !== 0)  ) 
                @if(mb_strlen($transform) !== 0)
                    <span class="hidden-xs {{ $transform }}">{{ $name }}</span> 
                @else 
                    {{ $name }} 
                @endif 
            @endif
            
        </a>
</li>

