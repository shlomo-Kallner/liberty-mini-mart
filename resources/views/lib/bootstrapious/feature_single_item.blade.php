
@php
    use \App\Utilities\Functions\Functions;

    $img2 = Functions::getBladedString($img??'','lib/bootstrapious/universal/img/portfolio-1.jpg');
    $imgAlt2 = Functions::getBladedString($imgAlt??'');
    $url2 = Functions::getBladedString($url??'','lib/bootstrapious/universal/portfolio-detail.html');
    $name2 = Functions::getBladedString($name??'','Portfolio box-image');
    $description2 = Functions::getBladedString($description??'','Pellentesque habitant morbi tristique senectus et netus et malesuada');
    $buttons2 = Functions::getUnBladedContent($buttons??'',[ ['url' => 'lib/bootstrapious/universal/portfolio-detail.html', 'caption' => 'View'], 
                                                             ['url' => '#', 'caption' => 'Website' ] ]);
                       
@endphp

    <div class="col-sm-4">
        <div class="box-image">
            <div class="image">
                <img src="{{ asset($img2) }}" alt="{{ $imgAlt2 }}" class="img-responsive">
            </div>
            <div class="bg"></div>
            <div class="name">
                <h3><a href="{{ url($url2) }}">{!! $name2 !!}</a></h3> 
            </div>
            <div class="text">
                <p class="hidden-sm">{!! $description2 !!}</p>
                @if (Functions::testVar($buttons2))
                    <p class="buttons">
                        @foreach ($buttons2 as $button)
                            <a href="{{ url($button['url']) }}" class="btn btn-template-transparent-primary">
                                {!! $button['caption'] !!}
                            </a>
                        @endforeach
                    </p>
                @endif
            </div>
        </div>
        <!-- /.box-image -->
    </div>
