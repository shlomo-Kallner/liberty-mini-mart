

<li>
    @if( isset($isModal) && $isModal === true )
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

