

@extends('content.template')

@section('main-content')
    @parent

    @component('lib.themewagon.article')
        @slot('pageHeader')
            {{ "Create a New Store Section" }}
        @endslot
        @slot('subheading')
            {{ "Here You Can Define a New Section For Your Store" }}
        @endslot
    @endcomponent
    <div class="row">
        @component('lib.themewagon.sidebar')
            
        @endcomponent

        <div class="col-md-9 col-sm-5">
            
            <form action="store/section" method="POST" role="form">
                {!! csrf_field() !!}
                
                <div class="form-group">
                    <label for="name">Section Name:</label><br>
                    <input type="text" class="form-control" id="name", name="name" placeholder="Your Section&apos;s Name..">
                </div>
                <div class="form-group">
                    <label for="url">Section Name:</label><br>
                    <input type="text" class="form-control" id="url", name="url" placeholder="Your Section&apos;s URL..">
                </div>
                <div class="form-group">
                    <label for="image">Section Picture:</label><br>
                    <input type="file" name="image" id="image">
                </div>
                <div class="form-group">
                    <label for="title">Your Section&apos;s Title:</label><br>
                    <textarea class="summerIDE" name="title" id="title" cols="30" rows="10">

                    </textarea>
                </div>
                <div class="form-group">
                    <label for="shortDescription"></label><br>
                    <textarea class="summerIDE" name="shortDescription" id="shortDescription" cols="30" rows="10">

                    </textarea>
                </div>
                <div class="form-group">
                    <label for="article"></label><br>
                    <textarea class="summerIDE" name="article" id="article" cols="30" rows="10">

                    </textarea>
                </div>

                
                

            
                
            
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            
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
@ensection

@section('js-extra')
    <script>
            jQuery(function($) {
                //set summernote on the textarea elements...
                $('.summerIDE').summernote();
            }):
    </script>
@endsection