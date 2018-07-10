
@php
    $testing = true;
    use \App\Utilities\Functions\Functions;
    
    $categories2 = Functions::getUnBladedContent($categories??'');
@endphp

<div class="panel-group" id="categories-panel-group" role="tablist" aria-multiselectable="true">
    @foreach ($categories2 as $category)
        @php
            $panelId1 = 'headingCategoryPanel' . $category['url'] . '-of-' . $category['section_name'];
            $panelId2 = 'collapseCategoryPanel' . $category['url'] . '-of-' . $category['section_name'];
        @endphp
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="{{ $panelId1 }}">
                <h3 class="panel-title">
                    <a role="button" data-toggle="collapse" 
                        data-parent="#categories-panel-group" href="{{ '#' . $panelId2 }}" 
                        aria-expanded="true" aria-controls="{{ $panelId2 }}">
                        {!! $category['name'] !!}
                    </a>
                </h3>
            </div>
            <div id="{{ $panelId2 }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{ $panelId1 }}">
                <div class="panel-body">
                    <div class="row">
        
                        <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 thumbnail">
                            <img src="{{ asset($category['img']) }}" class="img-responsive" alt="{{$category['imgAlt']}}">
                        </div>
                        
                        <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                            <div class="row">
                                <h4>{!! $category['title'] !!}</h4>
                            </div>
                            
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="btn-group">
                                        <a class="btn btn-default" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample" role="button">Show Details</a>
                                        <a class="btn btn-default" href="#" role="button">Edit</a>
                                        <a class="btn btn-default" href="#" role="button">Delete</a>
                                        <a class="btn btn-default" href="#" role="button">Show or Hide</a>
                                        <a class="btn btn-default" href="#" role="button">Move Up</a>
                                        <a class="btn btn-default" href="#" role="button">Move Down</a>   
                                    </div>
                                </div>
                            </div>
            
                            <div class="collapse">
                
                            </div>
                            
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
        
            
    @endforeach
    
</div>

