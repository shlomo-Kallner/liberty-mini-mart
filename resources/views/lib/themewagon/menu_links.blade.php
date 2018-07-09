
@php
/*
 * A template for a 'normal-link' or a 'modal-link' menu-item
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
 use App\Utilities\Functions\Functions,
    \App\Utilities\IterationStack\IterationStack,
    \App\Utilities\IterationStack\IterationFrame;
 
 $type2 = Functions::getBladedString($type??'','url');
 $target2 = Functions::getBladedString($target??'','');
 $cssExtraClasses2 = Functions::getBladedString($cssExtraClasses??'','');
 $url2 = Functions::getBladedString($url??'','#');
 $icon2 = Functions::getBladedString($icon??'','');
 $iconAfter2 = Functions::getBladedString($iconAfter??'','');
 $name2 = Functions::getBladedString($name??'','');
 $transform2 = Functions::getBladedString($transform??'','');

 if ($type2 == 'dropdown') {
    $listCSS2 = "dropdown" . Functions::getBladedString($listCSS??'','');
 }
 $toggle2 = Functions::getBladedString($toggle??'','');
 $role2 = Functions::getBladedString($role??'','');
 $listCSS2 = Functions::getBladedString($listCSS??'','');
 

@endphp

<li
@if (Functions::testVar($listCSS2))
    class="{!! $listCSS2 !!}"
@endif
>

    @component('lib.themewagon.links')
        @slot('type')
            {!! $type2 !!}
        @endslot
        @slot('target')
            {!! $target2 !!}
        @endslot
        @slot('cssExtraClasses')
            {!! $cssExtraClasses2 !!}
        @endslot
        @slot('url')
            {!! $url2 !!}
        @endslot
        @slot('icon')
            {!! $icon2 !!}
        @endslot
        @slot('iconAfter')
            {!! $iconAfter2 !!}
        @endslot
        @slot('name')
            {!! $name2 !!}
        @endslot
        @slot('transform')
            {!! $transform2 !!}
        @endslot
        @slot('toggle')
            {!! $toggle2 !!}
        @endslot
        @slot('role')
            {!! $role2 !!}
        @endslot
    @endcomponent

</li>
