$(function () {

    // init the validator
    // validator files are included in the download package
    // otherwise download from http://1000hz.github.io/bootstrap-validator

    $('#contact-form').validator();


    // when the form is submitted
    $('#contact-form').on('submit', function (e) {

        // if the validator does not prevent form submit
        if (!e.isDefaultPrevented()) {
            var url = "../mail.php";

            // POST values in the background the the script URL
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize(),
                success: function (data)
                {
                    // data = JSON object that contact.php returns

                    // we recieve the type of the message: success x danger and apply it to the 
                    var messageAlert = 'alert-' + data.type;
                    var messageText = data.message;

                    // let's compose Bootstrap alert box HTML
                    var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + messageText + '</div>';
                    
                    // If we have messageAlert and messageText
                    if (messageAlert && messageText) {
                        // inject the alert to .alerts div in our form
                        $('#contact-form').find('.alerts').html(alertBox);
                        // empty the form
                        $('#contact-form')[0].reset();
                    }
                }
            });
            return false;
        }
    })
});