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
    // $articleLegend2 = Functions::getBladedString($page['articleLegend']??'', 'Type Your Description Here.');
    $hasImage2 = Functions::getBladedString($page['hasImage']??'', '');
    $hasParent2 = Functions::getBladedString($page['hasParent']??'', '');
    $parentName2 = Functions::getBladedString($page['parentName']??'', 'Parent');
    $parentId2 = Functions::getBladedString($page['parentId']??'', 'parent');
    $parentList2 = Functions::getContent($page['parentList']??'', '');
    $hasSelectedParent2 = Functions::getBladedString($page['hasSelectedParent']??'', '');
    $selectedParent2 = Functions::getContent(
        $page['selectedParent']??'', Page::makeNameListing('No ' . $parentName2, '')
    );

@endphp

@section('main-content')
    @parent

    <div class="row margin-bottom-40 margin-top-40">
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
        </div>
        
        <div class="row">
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                
                <form action="{{ request()->fullUrl() }}" method="POST" role="form" enctype="multipart/form-data" novalidate="novalidate">
                    {{ csrf_field() }}
                    
                    @if (Functions::testVar($hasName2))
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $name2 }}" placeholder="Input Name.." required="required">
                        </div>
                    @endif

                    @if (Functions::testVar($hasTitle2))
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $title2 }}" placeholder="Input Title.." required="required">
                        </div>
                    @endif
                
                    @if (Functions::testVar($hasUrl2))
                        <div class="form-group">
                            <label for="url">URL:</label>
                            <input type="text" name="url" id="url" class="form-control" value="{{ $url2 }}" placeholder="Input URL" required="required">
                        </div>
                    @endif 
                
                    @if (Functions::testVar($hasDescription2))
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <input type="text" name="description" id="description" class="form-control" value="{{ $description2 }}" placeholder="Input Description" required="required">
                        </div>
                    @endif

                    @if (Functions::testVar($hasArticle2))
                        <fieldset>
                            {{--  
                                @if (Functions::testVar($articleLegend2))
                                    <legend>{{$articleLegend2}}</legend>    
                                    <br>
                                @endif  
                            --}}
                            <div class="row">
                                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                    <textarea name="article" id="articleSummernote" cols="50" rows="20"></textarea>
                                </div>
                            </div>
                            
                            <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#collapseSubheading" aria-expanded="false" aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> Add a Subheading
                            </button>
                            <div class="collapse" id="collapseSubheading">
                                <label for="subheading">Subheading:</label>
                                <input type="text" name="subheading" id="subheading" class="form-control" value="" placeholder="Input Subheading..">
                            </div>
                        </fieldset>
                    @endif

                    @if (Functions::testVar($hasImage2))
                        <div class="form-group">
                            <label for="image">Image File:</label>
                            <input type="file" accept="{{ Functions::getImageFileMIMETypeStr() }}" class="form-control" name="image" id="image">
                        </div>
                    @endif

                    @section('form-content')
                        
                    @show

                    @if (Functions::testVar($hasParent2))
                        <div class="form-group">
                            <label for="{{ $parentId2 }}">{{ $parentName2 }}:</label>
                            <select name="{{ $parentId2 }}" id="{{ $parentId2 }}">
                                <option value="{{ $selectedParent2['url'] }}" selected>
                                    {{ $selectedParent2['name'] }}
                                </option>
                                @if (Functions::testVar($parentList2))
                                    @foreach ($parentList2 as $item)
                                        <option value="{{ $item['url'] }}">{{ $item['name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @endif
                
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                    <input type="reset" value="Reset" class="btn btn-default">
                    <a class="btn btn-default pull-left" href="{{ url('admin') }}" role="button">Cancel</a>
                    
                </form>
                
            </div>
        </div>
        
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
        </div>
    </div>
@endsection



@section('css-preloaded')
    @parent 
    <!-- include summernote css -->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
@endsection

@section('js-defered')
    @parent
    <!-- include summernote js -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
@endsection

@section('js-extra')
    @parent
    <script>
        /** this script section is to be written here and then converted into a 
        *   Javascript file of its own and loaded here..
        **/

        jQuery(function($) 
        {
            $('#articleSummernote').summernote();
            
            
        });
    </script>
@endsection    
