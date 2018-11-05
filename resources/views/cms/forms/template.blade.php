@extends('content.template')

@php
    $testing = true;    
    use \App\Utilities\Functions\Functions;

    
    $hasName2 = Functions::getUnBladedContent($hasName??'', '');
    $name2 = Functions::getUnBladedContent($name??'', '');
    $hasTitle2 = Functions::getUnBladedContent($hasTitle??'', '');
    $title2 = Functions::getUnBladedContent($title??'', '');
    $hasUrl2 = Functions::getUnBladedContent($hasUrl??'', '');
    $url2 = Functions::getUnBladedContent($url??'', '');
    $hasArticle2 = Functions::getUnBladedContent($hasArticle??'', '');
    $articleLegend2 = Functions::getUnBladedContent($articleLegend??'', '');
    $hasImage2 = Functions::getUnBladedContent($hasImage??'', '');

@endphp

@section('main-content')
    @parent

    <div class="row">
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
        </div>
        
        <div class="row">
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                
                <form action="" method="POST" role="form" enctype="multipart/form-data" novalidate="novalidate">
                    
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

                    @if (Functions::testVar($hasArticle2))
                        <fieldset>
                            @if (Functions::testVar($articleLegend2))
                                <legend>{{$articleLegend2}}</legend>    
                                <br>
                            @endif
                            <textarea name="article" id="articleSummernote" cols="30" rows="10"></textarea>
                            <br>
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
