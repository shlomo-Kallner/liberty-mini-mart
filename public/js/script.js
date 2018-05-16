/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {
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
            $('.users-links').show();
            $('.icon-users-links-close').show();
        });

        $('.icon-users-links-close', panel).click(function () {
            $('.users-links').hide();
            $('.icon-users-links-close').hide();
        });

    };
    handleUsersLinks();
    
});
