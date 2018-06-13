


@php

    $testing = false;
    use \App\Utilities\Functions\Functions;

    if ($testing) {

        $containerClass2 = 'col-lg-4';
        $panelClass2 = 'panel-default';
        $panelHeader2 = 'Default Panel';
        $panelContent2 = '<p>
                            Lorem ipsum dolor sit amet, 
                            consectetur adipiscing elit. 
                            Vestibulum tincidunt est vitae 
                            ultrices accumsan. Aliquam 
                            ornare lacus adipiscing, 
                            posuere lectus et, 
                            fringilla augue.
                        </p>';
        $panelFooter2 = 'Panel Footer';

    } else {
        
        $containerClass2 = Functions::getBladedString($containerClass??'');
        $panelClass2 = Functions::getBladedString($panelClass??'');
        $panelHeader2 = Functions::getBladedString($panelHeader??'');
        $panelContent2 = Functions::getBladedString($panelContent??'');
        $panelFooter2 = Functions::getBladedString($panelFooter??'');;
    }


@endphp

    <div class="{{ $containerClass2 }}">
        <div class="panel {{ $panelClass2 }}">
            <div class="panel-heading">
                {!! $panelHeader2 !!}
            </div>
            <div class="panel-body">
                {!! $panelContent2  !!}
            </div>
            <div class="panel-footer">
                {!! $panelFooter2 !!}
            </div>
        </div>
    </div>
    <!-- end panel component -->