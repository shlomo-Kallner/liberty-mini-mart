
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
    @if( isset($type) && $type == 'modal' )
    <a href="#" data-toggle="modal" data-target="{{ $target }}">
        @else
        <a href="{{ url($url) }}">
            @endif
            @if($icon)
            <i class="fa {{ $icon }}" aria-hidden="true"></i>
            @endif
            @if($transform)
            <span class="hidden-xs {{ $transform }}">{{ $name }}</span>
            @else
            {{ $name }}
            @endif
        </a>
</li>

