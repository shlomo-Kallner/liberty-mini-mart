/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {
    //put your code here..
    //console.log("Ready To Code with jQuery!!");

    
    $('#topcontrol img').on('scroll resize', function(){
        alert('picture loading!');
        var picPath = '../lib/themewagon/metronicShopUI/theme/assets/corporate/img/up.png';
        if($(this).attr('src') != picPath){
            alert('changing picture!');
            $(this).attr('src', picPath);
        }
        
    });
});
