$(function(){
	$(".main-navigation li").click(function(){
	  $(".main-navigation li.active").removeClass("active");  
	  $(this).addClass("active");
	})

	var str=location.href.toLowerCase();
	$(".main-navigation li a").each(function() {
	  if(str.indexOf(this.href.toLowerCase())>-1){
	    $('.main-navigation li.active').removeClass("active");
	    $(this).parent("li").addClass("active");
	  }
	});


})


/*------------------------------------------
 Subscribe form ajax
 ------------------------------------------*/


$('#subscription-form').submit(function(e) {

    e.preventDefault();

    var $form           = $('#subscription-form');
    var submit          = $('#subscribe-button');
    var ajaxResponse    = $('#subscription-response');
    var email           = $('#subscriber-email').val();

    $.ajax({
        type: 'POST',
        url: 'assets/php/subscribe.php',
        dataType: 'json',
        data: {
            email: email
        },
        cache: false,
        beforeSend: function(result) {
            submit.val("Joining...");
        },
        success: function(result) {
            if(result.sendstatus == 1) {
                ajaxResponse.html(result.message);
                $form.fadeOut(500);
            } else {
                ajaxResponse.html(result.message);
                submit.val("Learn How");
            }
        }
    });

});

/*------------------------------------------
 Subscribe form ajax
 ------------------------------------------*/


$('.subscribe-form').submit(function(e) {

    e.preventDefault();
    var $form           = $('.subscribe-form');
    var submit          = $('.subscribe-button');
    var ajaxResponse    = $('.subscription-response');
    var email           = $('.subscriber-email').val();

    $.ajax({
        type: 'POST',
        url: 'assets/php/subscribe.php',
        dataType: 'json',
        data: {
            email: email
        },
        cache: false,
        beforeSend: function(result) {
            submit.val("Joining...");
        },
        success: function(result) {
            if(result.sendstatus == 1) {
                ajaxResponse.html(result.message);
                $form.fadeOut(500);
            } else {
                ajaxResponse.html(result.message);
                submit.val("Learn How");
            }
        }
    });

});
