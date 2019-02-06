@extends('cms.forms.template')


@section('form-content')
    @parent

    @php
        $testing = true;    
        use \App\Utilities\Functions\Functions,
        \App\Page;

        $price2 = Functions::getBladedString($page['price']??'', old('price'));
        $sale2 = Functions::getBladedString($page['sale']??'', old('sale'));
        $sectionList2 = Functions::getContent($page['lists']['sections']??'', '');
        $hasSelectedSection2 = Functions::getBladedString($page['hasSelectedSection']??'', '');
        $selectedParent2 = Functions::getContent(
        $page['selectedSection']??'', Page::makeNameListing('No Section', '')
    );
        
    @endphp
    
    
    <div class="form-group">
        <label for="price">Price:</label>
        <input type="text" name="price" id="price" class="form-control" value="{{ $price2 }}" placeholder="Input Price" required="required">
    </div>

    <div class="form-group">
        <label for="sale">Sale:</label>
        <input type="text" name="sale" id="sale" class="form-control" value="{{ $sale2 }}" placeholder="Input Price" required="required">
    </div>

    <div class="form-group">
        <label for="section">Section:</label>
        <select name="section" id="section">
            <option value="{{$selectedParent2['url']}}" selected>{{$selectedParent2['name']}}</option>
            @foreach ($sectionList2 as $item)
                <option value="{{ $item['url'] }}">{{ $item['name'] }}</option>
            @endforeach
        </select>
    </div>
    
    
                
                    
@endsection



@section('js-extra')
    @parent
    @php
        // $catListUrl = url('api/store/section');
    @endphp
    <script>
        /** this script section is to be written here and then converted into a 
        *   Javascript file of its own and loaded here..
        **/

        jQuery(
            function($) 
            {
                var lurl = window.Laravel.baseUrl + '/api/store/section/';
                var cat = $('.form-group > select#category');
                var sect = $('.form-group > select#section');
                sect.change(function () {
                    var nurl = lurl + $(this).val() + '/category/list';
                    var data = window.Laravel.handleCart.makeData(
                        {}, nurl, window.Laravel.csrfToken, '', 'list', 
                        window.Laravel.nut
                    );
                    var debug = true;
                    var _this = $(this);
                    window.Laravel.handleCart.doAjax($, data, 'GET', function (res) {
                        cat.empty();
                        $('<option value="">Pick a Category</option>').appendTo(cat);
                        for (var i of res) {
                            var opt = $('<option value="' + i.url + '">' + i.name + '</option>')
                            .appendTo(cat);
                        }
                        alert('Loading of Categories is Complete. Please Pick a Category.');
                    }, debug)
                });
            }
        );
    </script>
@endsection
