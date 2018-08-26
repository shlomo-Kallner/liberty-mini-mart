/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(function ($) {
    // put your code here..
    // console.log("Ready To Code with jQuery!!");

    // from
    /*

        BEGIN USERS Scrolled Links Panel Scripting

        Inspired From Styler Panel in 
        '..\lib\themewagon\metronicShopUI\theme\assets\corporate\scripts\layout.js'
    ***/

    var handleUsersLinks = function () {
    
        var panel = $('.users-links-panel');
    
        $('.icon-users-links', panel).click(function () {
            panel.addClass('icon-users-links-panel-open');
            $('.users-links').show();
            $('.icon-users-links-close').show();
        });

        $('.icon-users-links-close', panel).click(function () {
            panel.removeClass('icon-users-links-panel-open');
            $('.users-links').hide();
            $('.icon-users-links-close').hide();
        });

        // $('body:not(.page-header-fixed) .icon-users-links-panel-open');

    };
    handleUsersLinks();

    var handleSearch = function() {    

        /// set up handling the search-modal-trigger's click event
        $('.search-modal-trigger-btn').click(function() {
            $('#search-modal').modal('show');
            //console.log('helloOOOh World!');
        });

        /// 
        

    };
    handleSearch();

    /* var getOptionVals = function (options, jquery) {
        var result = {};
        for (var i in options) {
            result[i] = jquery('#' + options[i]).val();
        }
        return result;
    }; */

    //var cartForStore = function () {
    var handleCart = {
        // utility 
        getOptionVals: function (options, jquery) {
            var result = {};
            for (var i in options) {
                result[i] = jquery('#' + options[i]).val();
            }
            return result;
        },
        doAjax: function (jquery, data) {
            jquery.ajax(
                {
                    url: data.url,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': data.token //'{{ csrf_token() }}'
                    },
                    type: 'POST',
                    data: data,
                    success: function (result, status, xhr) {
                        console.log(status + ' -> ' + JSON.stringify(result));
                        if (data.redirect) {
                            window.location.assign(data.redirect);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(status + ' -> ' + error);
                    }
                }
            );
        },
        getData: function (item, token, numProducts) {
            //var options = item.data('productOption');
            var data = {
                options: handleCart.getOptionVals(item.data('productOption')),
                id: item.data('productId'),
                url: item.data('productUrl'),
                numProducts: numProducts,
                token: token,
                redirect: item.data('redirectTo')
            };
            return data;
        }
    };
       // return cartForStore;
    //};
    /* jQuery.fn.extend({
        cartForStore: handleCart()
    }); */

    var myInit = function ($) {
        Layout.init();
        Layout.initOWL();
        Layout.initImageZoom();
        Layout.initTouchspin();
        Layout.initFixHeaderWithPreHeader();
        Layout.initNavScrolling();
        Layout.initUniform();
        Layout.initSliderRange();
        $.scrolltotop.init2(window.Laravel.upPngPath);
      };
    myInit($);  

  var checkTimeOut = function ($) {
    var alertTimeout = window.Laravel.page.alert.getTimeout();
    // console.log("Heloo from checkTimeOut()! timeout = " + alertTimeout);  
    if (alertTimeout !== 0) {
      var jMe = $('#masterPageAlert');
      jMe.show(400, function() {
        setTimeout(function() {
            jMe.hide();
        }, alertTimeout);
      });
    }
  };
  checkTimeOut($);

  $('.addToCart').on('click', function(e) {
      var data = handleCart.getData($(this), window.Laravel.csrfToken, 1);
      handleCart.doAjax($, data);
      
    });
  $('.orderNow').on('click', function(e) {
    var data = handleCart.getData($(this), window.Laravel.csrfToken, 1);
    handleCart.doAjax($, data);

        //$.ajax()
    });
  //$('.delFromCart')

});
