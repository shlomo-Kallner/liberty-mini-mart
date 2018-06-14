/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(function ($) {
    //put your code here..
    //console.log("Ready To Code with jQuery!!");

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

        //$('body:not(.page-header-fixed) .icon-users-links-panel-open');

    };
    handleUsersLinks();

    var handleSearch = function() {    

        /// set up handling the search-modal-trigger's click event
        $('.search-modal-trigger-btn').click(function(){
            $('#search-modal').modal('show');
        });

        /// 
        

    };
    //handleSearch();
    
    /// set up handling the search-modal-trigger's click event
    $('.search-modal-trigger-btn').click(function(){
        $('#search-modal').modal('show');
    });


    var getOptionVals = function (options, jquery)
        {
            var result = {};
            for(var i in options){
                result[i] = jquery(options[i]).val();
            }
            return result;
        };

    var handleCart = function() {
        var cartForStore = {
            // utility 
            getOptionVals: function (options, jquery)
            {
                var result = {};
                for(var i in options){
                    result[i] = jquery(options[i]).val();
                }
                return result;
            },
        
        };

    };
    
});
