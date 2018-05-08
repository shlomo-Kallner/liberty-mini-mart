
<?php
/*
 * A template for a menu item ..
 * 
 * $templateItem = [
  'icon' => '', // the Font Awesome 4 icon class without the 'fa'.
  'name' => '', // the name to fill in the Link.
  'url' => '', // the URL of the link.
  //'isModal' => false, // a Boolean, Is this a Modal or a URL? -@OBSOLETE!!
  'type' => 'url', // 'url' for a url link, 'modal' for a modal button link.. -@NEW!
  // 'type' replaces 'isModal'!
  'target' => '', // the data-target attribute's data value (of a modal)
  'transform' => '', // Bootstrap 3 text-transform css class.
  ];
 * 
 */
?>
<li>
    <?php //dd($isModal); ?>
    @php
    //dd($type);
    @endphp

    @if( isset($type) && ($type != 'url') )
        @if( $type == 'modal' )
            <a href="#" data-toggle="modal" data-target="{{ $target }}">
        @elseif( $type == 'dropdown' || $type == 'dropdown-submenu' )
        
            {{-- DROPDOWNS ARE AN ADVANCED-TASK COMPONENT -> NOT IMPLEMENTED YET! --}}
            {{-- DROPDOWN-SUBMENUS ARE A WISHLIST-TASK COMPONENT -> NOT IMPLEMENTED YET! --}}

        @endif
    @else
        <a href="{{ url($url) }}">
    @endif

            @if( isset($icon) && (mb_strlen($icon) !== 0) )
            <i class="fa {{ $icon }}" aria-hidden="true"></i>
            @endif

            @php 
            //dd($transform); 
            @endphp

            
            @if ( isset($name) && (mb_strlen($name) !== 0)  ) 
                @if(mb_strlen($transform) !== 0)
                    <span class="hidden-xs {{ $transform }}">{{ $name }}</span> 
                @else 
                    {{ $name }} 
                @endif 
            @endif
            
            @if( isset($type) )
        
                @if ($type == 'dropdown')
                    {{-- DROPDOWNS ARE AN ADVANCED-TASK COMPONENT -> NOT IMPLEMENTED YET! --}}
                
                @elseif ($type == 'dropdown-submenu')
                    {{-- DROPDOWN-SUBMENUS ARE A WISHLIST-TASK COMPONENT -> NOT IMPLEMENTED YET! --}}
                    
                @endif
                
            
            @endif
            
        </a>
</li>

