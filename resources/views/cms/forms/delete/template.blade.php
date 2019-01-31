@extends('content.template')

@php
    $testing = true;    
    use \App\Utilities\Functions\Functions,
        \App\Page;

    
    $hasName2 = Functions::getBladedString($page['hasName']??'', '');
    $name2 = Functions::getBladedString($page['name']??'', old('name',''));
    $hasTitle2 = Functions::getBladedString($page['hasTitle']??'', '');
    $title2 = Functions::getBladedString($page['title']??'', old('title',''));
    $hasUrl2 = Functions::getBladedString($page['hasUrl']??'', '');
    $url2 = Functions::getBladedString($page['url']??'', old('url',''));
    $hasDescription2 = Functions::getBladedString($page['hasDescription']??'', '');
    $description2 = Functions::getBladedString($page['description']??'', old('description',''));
    $hasArticle2 = Functions::getBladedString($page['hasArticle']??'', '');
    $article2 = Functions::getBladedString($page['article']??'', old('article',''));
    $hasSubHeading2 = Functions::getBladedString($page['hasSubHeading']??'', '');
    $subHeading2 = Functions::getBladedString($page['subHeading']??'', '');
    // $articleLegend2 = Functions::getBladedString($page['articleLegend']??'', 'Type Your Description Here.');
    $hasImage2 = Functions::getBladedString($page['hasImage']??'', '');
    $image2 = Functions::getContent($page['image']??'', '');
    $hasParent2 = Functions::getBladedString($page['hasParent']??'', '');
    $parentName2 = Functions::getBladedString($page['parentName']??'', 'Parent');
    $parentId2 = Functions::getBladedString($page['parentId']??'', 'parent');
    $parentList2 = Functions::getContent($page['parentList']??'', '');
    $hasSelectedParent2 = Functions::getBladedString($page['hasSelectedParent']??'', '');
    $selectedParent2 = Functions::getContent(
        $page['selectedParent']??'', Page::makeNameListing('No ' . $parentName2, '')
    );
    $thisURL2 = Functions::getBladedString($page['thisURL']??'', request()->fullUrl());

@endphp

@section('main-content')
    @parent

    <div class="row margin-bottom-40 margin-top-40">
        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
        </div>
        
        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">

            <h1>Are You Sure You Want To Delete?</h1>
            
            <form action="{{ $thisURL2 }}" method="POST" role="form" enctype="multipart/form-data" novalidate="novalidate">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                
                @if (Functions::testVar($hasName2))
                    <h3>Name:</h3>
                    <div class="well well-sm">
                        <h3>{{ $name2 }}</h3> 
                    </div>
                @endif

                @if (Functions::testVar($hasTitle2))
                    <h3>Title:</h3>
                    <div class="well well-sm">
                        <h3>{{ $title2 }}</h3> 
                    </div>
                @endif
            
                @if (Functions::testVar($hasUrl2))
                    <h3>URL:</h3>
                    <div class="well well-sm">
                        <h3>{{ $url2 }}</h3> 
                    </div>
                @endif 
            
                @if (Functions::testVar($hasDescription2))
                    <h3>Description:</h3>
                    <div class="well well-sm">
                        <h3>{{ $description2 }}</h3> 
                    </div>
                @endif

                @if (Functions::testVar($hasArticle2))

                    <div class="row">
                        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                            <h3>Subheading:</h3> 
                            <div class="well well-sm">
                                <h3>{{ $subHeading2 }}</h3> 
                            </div>
                            <h3>Article:</h3>
                            <div class="well well-lg">
                                <textarea cols="100" rows="20">{{ $article2 }}</textarea>
                            </div>
                        </div>
                    </div>

                @endif

                @if (Functions::testVar($hasImage2))
                
                    <div class="well well-lg">
                        <h3>Image File:</h3>
                        @if (Functions::testVar($image2))
                            @component('inc.figure')
                                @foreach ($image2 as $key => $item)
                                    @slot($key)
                                        {!! Functions::toBladableContent($item) !!}
                                    @endslot
                                @endforeach 
                            @endcomponent
                        @endif
                    </div>

                @endif

                @section('form-content')
                    
                @show

                @if (Functions::testVar($hasParent2))
                    
                    <h3>{{ $parentName2 }}:</h3>
                    <div class="well well-sm">
                        <h3>
                            @component('lib.themewagon.links')
                                @foreach (Page::genURLMenuItem($selectedParent2['url'], $selectedParent2['name']) as $key => $item)
                                    @slot($key)
                                        {!! Functions::toBladableContent($item) !!}
                                    @endslot
                                @endforeach
                            @endcomponent
                        </h3>
                    </div>
                    
                @endif
            
                <button type="submit" class="btn btn-primary pull-right">Delete</button>
                <a class="btn btn-default pull-left" href="{{ url('admin') }}" role="button">Cancel</a>
                
            </form>
            
        </div>
        
        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
        </div>
    </div>
@endsection
