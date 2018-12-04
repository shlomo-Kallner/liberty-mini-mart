/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(function ($) {
  // put your code here..
  // console.log("Ready To Code with jQuery!!");
  /*

    BEGIN USERS Scrolled Links Panel Scripting

    Inspired From Styler Panel in 
    '..\lib\themewagon\metronicShopUI\theme\assets\corporate\scripts\layout.js'
  */

  var handleUsersLinks = function () {
    var panel = $('.users-links-panel')

    $('.icon-users-links', panel).click(function () {
      panel.addClass('icon-users-links-panel-open')
      $('.users-links').show()
      $('.icon-users-links-close').show()
    })

    $('.icon-users-links-close', panel).click(function () {
      panel.removeClass('icon-users-links-panel-open')
      $('.users-links').hide()
      $('.icon-users-links-close').hide()
    })

    // $('body:not(.page-header-fixed) .icon-users-links-panel-open')
  }
  handleUsersLinks()

  var handleSearch = function () {
    /// set up handling the search-modal-trigger's click event
    $('.search-modal-trigger-btn').click(
      function () {
        $('#search-modal').modal('show')
        // console.log('helloOOOh World!');
      }
    )
  }
  handleSearch()

  var handleCartInit = function () {
    $('.product-quantity #product-quantity').TouchSpin({
      buttondown_class: 'btn quantity-down',
      buttonup_class: 'btn quantity-up'
    });
    $('.quantity-down').html("<i class='fa fa-angle-down'></i>")
    $('.quantity-up').html("<i class='fa fa-angle-up'></i>")
  }
  // handleCartInit()

  /*
    var getOptionVals = function (options, jquery) {
        var result = {}
        for (var i in options) {
            result[i] = jquery('#' + options[i]).val()
        }
        return result
    }
  */

  // var cartForStore = function () {
  var handleCart = {
        // utility 
        getOptionVals: function (options, jquery) {
            var result = {};
            for (var i in options) {
                result[i] = jquery('#' + options[i]).val();
            }
            return result;
        },
        doAjax: function (jquery, data, type = 'POST', callback = null) {
            handleCart.dumpData(data);
            jquery.ajax(
                {
                    url: data.url,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': data.token,
                        'X-XSRF-TOKEN': data._token
                        // 'XSRF-TOKEN': data.token,
                        // 'Cookie': document.cookie
                    },
                    type: type,
                    data: data,
                    xhrFields: {
                       withCredentials: true
                    },
                    success: function (result, status, xhr) {
                        // console.log(status + ' -> ' + JSON.stringify(result));
                        console.log(status + ' -> ');
                        // handleCart.dumpData(xhr);
                        if (callback) {
                            callback(result);
                        }
                        handleCart.dumpData(result);
                        if (data.redirect) {
                            window.location.assign(data.redirect);
                        } else if (result.redirect) {
                            window.location.assign(result.redirect);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(status + ' -> ' + error);
                    }
                }
            );
        },
        getData: function (item, token, numProducts, action, nut = '') {
            // var options = item.data('productOption');
            return handleCart.makeData(
                {
                    id: item.data('productId'),
                    options: handleCart.getOptionVals(item.data('productOption')),
                    numProducts: numProducts
                }, item.data('productUrl'), token,
                item.data('redirectTo'), action, nut
            );
        },
        makeData: function (info, url, token, redirect, action, nut = '') {
            return {
                /* data: function (name) {
                    if (handleCart.testData(this[name])) {
                        return this[name];
                    } else {
                        return null;
                    }
                }, */
                info: info,
                url: url,
                token: token,
                _token: token,
                redirect: redirect,
                action: action,
                nut: nut
            };
        },
        testData: function (val) {
            if (val === null || val === undefined || !val) {
                return false;
            } else if (val === '') {
                return false;
            } else if (val === 0 || val === 0.0) {
                return false;
            } else {
                return true;
            }
        },
        addToCart: function (item) {
            var tmp = $('.product-quantity input[id="product-quantity"]').val();
            var val = handleCart.testData(tmp) ? tmp : 1;
            var data = handleCart.getData(
                item, window.Laravel.csrfToken, val, 'addToCart',
                window.Laravel.nut
            );
            var callback = function (result) {
                window.Laravel.page.setCart(result);
            };
            handleCart.doAjax($, data, 'POST', callback);
        },
        remFromCart: function (item) {
            var data = handleCart.getData(
                item, window.Laravel.csrfToken, 1, 'remFromCart',
                window.Laravel.nut
            );
            var callback = function (result) {
                window.Laravel.page.setCart(result);
            };
            handleCart.doAjax($, data, 'POST', callback);
        },
        delFromCart: function (item) {
            var info = {
                id: item.data('cartItemId'),
                numProducts: item.data('cartItemQuantity')
            };
            var url = item.data('cartApiUrl');
            var data = handleCart.makeData(
                info, url, window.Laravel.csrfToken, 
                '', 'delFromCart', window.Laravel.nut
            );
            var callback = function (result) {
                window.Laravel.page.setCart(result);
            };
            ///
            handleCart.doAjax($, data, 'POST', callback);
        },
        isScalar: function (data) {
            if (typeof data === 'boolean' 
                || typeof data === 'number'
                || typeof data === 'string'
                || typeof data === 'undefined'
            ) {
                return true;
            } else if (typeof data === 'object') {
                return false;
            } else {
                return false;
            }

        },
        dumpData: function (data) {
            for (var i in data) {
                if (handleCart.isScalar(i)) {
                    console.log( i + ' => ' + data[i]);
                } else if (typeof i === 'object') {
                    console.log( i + ' => [ ');
                    handleCart.dumpData(data[i]);
                    console.log(']');
                }
            }
        }
  }
  window.Laravel.handleCart = handleCart
  // return cartForStore;
  // };
  /* 
    jQuery.fn.extend({
        cartForStore: handleCart()
    }); 
  */

  var myInit = function ($) {
    Layout.init()
    Layout.initOWL()
    Layout.initImageZoom()
    Layout.initTouchspin()
    Layout.initFixHeaderWithPreHeader()
    Layout.initNavScrolling()
    Layout.initUniform()
    Layout.initSliderRange()
    $.scrolltotop.init2(window.Laravel.upPngPath)
  }
  myInit($)

  var checkTimeOut = function ($) {
    var alertTimeout = window.Laravel.page.alert.getTimeout()
    // console.log("Heloo from checkTimeOut()! timeout = " + alertTimeout) 
    if (alertTimeout !== 0) {
      var jMe = $('#masterPageAlert')
      jMe.show(400, function () {
        setTimeout(function () {
          jMe.hide()
        }, alertTimeout)
      })
    }
  }
  // checkTimeOut($);

  $('.addToCart').on('click', function (e) {
    handleCart.addToCart($(this))
  })
  $('.orderNow').on('click', function (e) {
    handleCart.addToCart($(this))
  })
  $('.delFromCart').on('click', function (e) {
    handleCart.delFromCart($(this))
    // console.log('in .delFromCart');
  })
  // $('.del-goods').on('click', function (e) {
  //    handleCart.delFromCart($(this));
  //    console.log('in .del-goods');
  // });

});
