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
    
@endphp

<div class="container">
    <div class="row">
        <div class="col-md-12" id="masterPageAlertContainer">

            @if (Functions::testVar($class2) && Functions::testVar($title2) && Functions::testVar($content2))

                <div id="masterPageAlert" class="alert {{ $class2 }} alert-dismissible fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                        <i class="fa fa-close" aria-hidden="true"></i>
                    </button>
                    <strong>{{ $title2 }}</strong> 
                    {!! $content2 !!}
                </div>

            @endif
            
        </div>
        <script>
            @php
                $js_data = [
                    'class' => $class2,
                    'title' => $title2,
                    'content' => $content2,
                    'timeout' => $timeout2
                ];
            @endphp
            window.Laravel.alert = "@json($js_data)";
        </script>
    </div>
</div>