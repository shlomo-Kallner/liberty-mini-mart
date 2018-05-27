
@php
    use \App\Utilities\Functions\Functions;

    $heading2 = Functions::getBladedString($heading??'');
    $items2 = Functions::getBladedString($items??'');

@endphp

{{-- BEGIN HEADING --}}
    <div class="heading text-center">
    @if (true)

        <h3>Latest from our workshop</h3>
        
    @else
        <h3>{!! $heading2 !!}</h3>
    @endif
    </div>
{{-- END HEADING --}}

{{-- BEGIN PORTFOLIO BOXES --}}
    <div class="row portfolio no-space">
        @if (true)
        
            <div class="col-sm-4">
                <div class="box-image">
                    <div class="image">
                        <img src="{{ asset('lib/bootstrapious/universal/img/portfolio-1.jpg') }}" alt="" class="img-responsive">
                    </div>
                    <div class="bg"></div>
                    <div class="name">
                        <h3><a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}">Portfolio box-image</a></h3> 
                    </div>
                    <div class="text">
                        <p class="hidden-sm">Pellentesque habitant morbi tristique senectus et netus et malesuada</p>
                        <p class="buttons">
                            <a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}" class="btn btn-template-transparent-primary">View</a>
                            <a href="#" class="btn btn-template-transparent-primary">Website</a>
                        </p>
                    </div>
                </div>
                <!-- /.box-image -->
            </div>

            <div class="col-sm-4">
                <div class="box-image">
                    <div class="image">
                        <img src="{{ asset('lib/bootstrapious/universal/img/portfolio-2.jpg') }}" alt="" class="img-responsive">
                    </div>
                    <div class="bg"></div>
                    <div class="name">
                        <h3><a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}">Portfolio box-image</a></h3> 
                    </div>
                    <div class="text">
                        <p class="hidden-sm">Pellentesque habitant morbi tristique senectus et netus et malesuada</p>
                        <p class="buttons">
                            <a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}" class="btn btn-template-transparent-primary">View</a>
                            <a href="#" class="btn btn-template-transparent-primary">Website</a>
                        </p>
                    </div>
                </div>
                <!-- /.box-image -->
            </div>

            <div class="col-sm-4">
                <div class="box-image">
                    <div class="image">
                        <img src="{{ asset('lib/bootstrapious/universal/img/portfolio-3.jpg') }}" alt="" class="img-responsive">
                    </div>
                    <div class="bg"></div>
                    <div class="name">
                        <h3><a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}">Portfolio box-image</a></h3> 
                    </div>
                    <div class="text">
                        <p class="hidden-sm">Pellentesque habitant morbi tristique senectus et netus et malesuada</p>
                        <p class="buttons">
                            <a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}" class="btn btn-template-transparent-primary">View</a>
                            <a href="#" class="btn btn-template-transparent-primary">Website</a>
                        </p>
                    </div>
                </div>
                <!-- /.box-image -->
            </div>

            <div class="col-sm-4">
                <div class="box-image">
                    <div class="image">
                        <img src="{{ asset('lib/bootstrapious/universal/img/portfolio-4.jpg') }}" alt="" class="img-responsive">
                    </div>
                    <div class="bg"></div>
                    <div class="name">
                        <h3><a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}">Portfolio box-image</a></h3> 
                    </div>
                    <div class="text">
                        <p class="hidden-sm">Pellentesque habitant morbi tristique senectus et netus et malesuada</p>
                        <p class="buttons">
                            <a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}" class="btn btn-template-transparent-primary">View</a>
                            <a href="#" class="btn btn-template-transparent-primary">Website</a>
                        </p>
                    </div>
                </div>
                <!-- /.box-image -->
            </div>

            <div class="col-sm-4">
                <div class="box-image">
                    <div class="image">
                        <img src="{{ asset('lib/bootstrapious/universal/img/portfolio-5.jpg') }}" alt="" class="img-responsive">
                    </div>
                    <div class="bg"></div>
                    <div class="name">
                        <h3><a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}">Portfolio box-image</a></h3> 
                    </div>
                    <div class="text">
                        <p class="hidden-sm">Pellentesque habitant morbi tristique senectus et netus et malesuada</p>
                        <p class="buttons">
                            <a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}" class="btn btn-template-transparent-primary">View</a>
                            <a href="#" class="btn btn-template-transparent-primary">Website</a>
                        </p>
                    </div>
                </div>
                <!-- /.box-image -->
            </div>

            <div class="col-sm-4">
                <div class="box-image">
                    <div class="image">
                        <img src="{{ asset('lib/bootstrapious/universal/img/portfolio-6.jpg') }}" alt="" class="img-responsive">
                    </div>
                    <div class="bg"></div>
                    <div class="name">
                        <h3><a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}">Portfolio box-image</a></h3> 
                    </div>
                    <div class="text">
                        <p class="hidden-sm">Pellentesque habitant morbi tristique senectus et netus et malesuada</p>
                        <p class="buttons">
                            <a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}" class="btn btn-template-transparent-primary">View</a>
                            <a href="#" class="btn btn-template-transparent-primary">Website</a>
                        </p>
                    </div>
                </div>
                <!-- /.box-image -->
            </div>

            <div class="col-sm-4">
                <div class="box-image">
                    <div class="image">
                        <img src="{{ asset('lib/bootstrapious/universal/img/portfolio-7.jpg') }}" alt="" class="img-responsive">
                    </div>
                    <div class="bg"></div>
                    <div class="name">
                        <h3><a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}">Portfolio box-image</a></h3> 
                    </div>
                    <div class="text">
                        <p class="hidden-sm">Pellentesque habitant morbi tristique senectus et netus et malesuada</p>
                        <p class="buttons">
                            <a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}" class="btn btn-template-transparent-primary">View</a>
                            <a href="#" class="btn btn-template-transparent-primary">Website</a>
                        </p>
                    </div>
                </div>
                <!-- /.box-image -->
            </div>

            <div class="col-sm-4">
                <div class="box-image">
                    <div class="image">
                        <img src="{{ asset('lib/bootstrapious/universal/img/portfolio-9.jpg') }}" alt="" class="img-responsive">
                    </div>
                    <div class="bg"></div>
                    <div class="name">
                        <h3><a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}">Portfolio box-image</a></h3> 
                    </div>
                    <div class="text">
                        <p class="hidden-sm">Pellentesque habitant morbi tristique senectus et netus et malesuada</p>
                        <p class="buttons">
                            <a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}" class="btn btn-template-transparent-primary">View</a>
                            <a href="#" class="btn btn-template-transparent-primary">Website</a>
                        </p>
                    </div>
                </div>
                <!-- /.box-image -->
            </div>

            <div class="col-sm-4">
                <div class="box-image">
                    <div class="image">
                        <img src="{{ asset('lib/bootstrapious/universal/img/portfolio-8.jpg') }}" alt="" class="img-responsive">
                    </div>
                    <div class="bg"></div>
                    <div class="name">
                        <h3><a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}">Portfolio box-image</a></h3> 
                    </div>
                    <div class="text">
                        <p class="hidden-sm">Pellentesque habitant morbi tristique senectus et netus et malesuada</p>
                        <p class="buttons">
                            <a href="{{ url('lib/bootstrapious/universal/portfolio-detail.html') }}" class="btn btn-template-transparent-primary">View</a>
                            <a href="#" class="btn btn-template-transparent-primary">Website</a>
                        </p>
                    </div>
                </div>
                <!-- /.box-image -->
            </div>

        @else
            @foreach ($items2 as $item)
                @component('lib.bootstrapious.feature_single_item')
                    @slot('img')
                        {!! $item['img'] !!}
                    @endslot
                    @slot('name')
                        {!! $item['name'] !!}
                    @endslot
                    @slot('url')
                        {!! $item['url'] !!}
                    @endslot
                    @slot('description')
                        {!! $item['description'] !!}
                    @endslot
                    @slot('buttons')
                        {!! serialize($item['buttons']) !!}
                    @endslot
                @endcomponent
            @endforeach
        @endif
    </div>
{{-- END PORTFOLIO BOXES --}}

    <div class="see-more">
        <a href="{{ url('lib/bootstrapious/universal/portfolio-4.html') }}" class="btn btn-template-main">See more of our work</a>
    </div> 
