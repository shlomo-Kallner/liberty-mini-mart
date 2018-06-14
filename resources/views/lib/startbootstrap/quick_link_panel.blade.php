
@php

    $testing = false;
    use \App\Utilities\Functions\Functions;

    if ($testing) {
        $containerCss2 = 'col-lg-3 col-md-6';
        $panelClass2 = 'panel-primary';
        $headerIcon2 = 'fa-comments';
        $numContent2 = '26';
        $contentTitle2 = 'New Comments!';
        $panelLinkUrl2 = '#';
    } else {
        $containerCss2 = Functions::getBladedString($containerCss??'','');
        $panelClass2 = Functions::getBladedString($panelClass??'','');
        $headerIcon2 = Functions::getBladedString($headerIcon??'','');
        $numContent2 = Functions::getBladedString($numContent??'','');
        $contentTitle2 = Functions::getBladedString($contentTitle??'','');
        $panelLinkUrl2 = Functions::getBladedString($panelLinkUrl??'','');
    }
    
@endphp


    <div class="{{ $containerCss2 }}">
        <div class="panel {{ $panelClass2 }}">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa {{ $headerIcon2 }} fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $numContent2 }}</div>
                        <div>{{ $contentTitle2 }}</div>
                    </div>
                </div>
            </div>
            <a href="{{ url($panelLinkUrl2) }}">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    