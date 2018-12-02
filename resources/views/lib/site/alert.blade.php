@php
    use \App\Utilities\Functions\Functions;

    $class2 = Functions::getBladedString($class??'');
    $title2 = Functions::getBladedString($title??'');
    $content2 = Functions::getBladedString($content??'');
    $timeout_t = Functions::getBladedString($timeout??9000);

    
    if (is_int($timeout_t)) {
        $timeout2 = $timeout_t > 0 ? $timeout_t : 0;
    } elseif (is_string($timeout_t)) {
        $timeout2 = $timeout_t === 'zero'? 0 : intval($timeout_t);
    } else {
        $timeout2 = '';
    }

    //dd($class, $title, $content, $timeout);
    //dd($class2, $title2, $content2, $timeout2);
    if (Functions::testVar($class2) && Functions::testVar($title2) && Functions::testVar($content2)) {
        $display = true;
    } else {
        $display = false;
    }
    $js_data = [
        'class' => $class2,
        'title' => $title2,
        'content' => str_replace("\n", '',$content2),
        'timeout' => $timeout2
    ];
@endphp

<div class="container">
    <div class="row">
        <div class="col-md-12" id="masterPageAlertContainer">

            <div id="masterPageAlert" class="alert {{ $class2 }} 
                {{ true ?'alert-dismissible fade in' : '' }}" 
                style="display: {{ $display ? 'block' : 'none' }};" 
                data-server-rendered="true">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    <i class="fa fa-close" aria-hidden="true"></i>
                </button>
                <strong>{{ $title2 }}</strong> 
                {!! $content2 !!}
            </div>

        </div>
        <script>
            window.Laravel.alert = '@json($js_data)';
        </script>
    </div>
</div>

@section('js-extra')
    @parent
    <script>
        window.Laravel.page.setAlert('@json($js_data)');
    </script>
@endsection