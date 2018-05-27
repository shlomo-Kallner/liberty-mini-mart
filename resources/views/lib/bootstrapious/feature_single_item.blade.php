
@php
    $img = '';
    $url = '';
    $name = '';
    $description = '';
    $buttons = [];
@endphp

    <div class="col-sm-4">
        <div class="box-image">
            <div class="image">
                <img src="{{ asset($img) }}" alt="" class="img-responsive">
            </div>
            <div class="bg"></div>
            <div class="name">
                <h3><a href="{{ url($url) }}">{!! $name !!}</a></h3> 
            </div>
            <div class="text">
                <p class="hidden-sm">{!! $description !!}</p>
                <p class="buttons">
                    @foreach ($buttons as $button)
                        <a href="{{ url($button['url']) }}" class="btn btn-template-transparent-primary">
                            {!! $button['caption'] !!}
                        </a>
                    @endforeach
                </p>
            </div>
        </div>
        <!-- /.box-image -->
    </div>