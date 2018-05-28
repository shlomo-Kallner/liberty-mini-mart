
@php
    //$testing = true;
    use \App\Utilities\Functions\Functions;
    $heading2 = Functions::getBladedString($heading??'');
    $img2 = Functions::getBladedString($img??'','lib/bootstrapious/universal/img/portfolio-4.jpg');
    $imgAlt2 = Functions::getBladedString($imgAlt??'');
    $url2 = Functions::getBladedString($url??'','lib/bootstrapious/universal/portfolio-detail.html');
    $name2 = Functions::getBladedString($name??'','Portfolio item');
    $description2 = Functions::getBladedString($description??'','This is the lead paragraph of the article. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget.');
    $article2 = Functions::getBladedString($article??'','Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper.
    Aenean ultricies mi vitae est. Mauris placerat eleifend leo.');
    $buttons2 = Functions::getUnBladedContent($buttons??'',[[ 'url' => 'lib/bootstrapious/universal/portfolio-detail.html',
                                                              'caption' => 'View' ]]);
@endphp

    <div class="heading text-center">
        @if (Functions::testVar($heading2))
            <h2>{!! $heading2 !!}</h2>
        @else
            <h2>Featured project</h2>
        @endif
    </div>

    <div class="row">
        <div class="portfolio-showcase clearfix">
            <div class="col-sm-6">
                <div class="image">
                    <img src="{{ asset($img2) }}" alt="{{ $imgAlt2 }}" class="img-responsive">
                </div>
            </div>

            <div class="col-sm-6">
                <h3><a href="{{ url($url2) }}">{!! $name2 !!}</a></h3>
                <p class="lead">{!! $description2 !!}</p>
                <p>{!! $article2 !!}</p>
                @if (Functions::testVar($buttons2))
                    <p class="buttons">
                        @foreach ($buttons2 as $item)
                            <a href="{{ url($item['url']) }}" class="btn btn-template-main">
                                {!! $item['caption'] !!}
                            </a>
                        @endforeach
                    </p>
                @endif
            </div>
        </div>
    </div>

    