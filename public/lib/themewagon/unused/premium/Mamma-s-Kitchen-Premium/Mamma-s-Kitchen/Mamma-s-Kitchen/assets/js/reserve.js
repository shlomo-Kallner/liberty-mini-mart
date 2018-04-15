/*------------------------------------------
 Contact form
 ------------------------------------------*/

$(document).ready(function () {

    $("#reservationForm").submit(function(e){

        e.preventDefault();
        var $ = jQuery;

        var postData 		= $(this).serializeArray(),
            formURL 		= $(this).attr("action"),
            $rfResponse 	= $('#reservationFormResponse'),
            $rfsubmit 		= $("#rfsubmit"),
            rfsubmitText 	= $rfsubmit.text();

        $rfsubmit.text("Sending...");


        $.ajax(
            {
                url : formURL,
                type: "POST",
                data : postData,
                success:function(data)
                {
                    $rfResponse.html(data);
                    $rfsubmit.text(rfsubmitText);
                },
                error: function(data)
                {
                    alert("Error occurd! Please try again");
                }
            });

        return false;

    });
});


