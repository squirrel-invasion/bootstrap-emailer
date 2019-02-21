$(function () {

    $('#gp-contact-form-two').validator();

    $('#gp-contact-form-two').on('submit', function (e) {
        if (!e.isDefaultPrevented()) {
            var url = "mail.php";

            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize(),
                success: function (data)
                {
                    var messageAlert = 'alert-' + data.type;
                    var messageText = data.message;

                    var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + messageText + '</div>';
                    if (messageAlert && messageText) {
                        $('#gp-contact-form-two').find('.messages').html(alertBox);
                        $('#gp-contact-form-two')[0].reset();
                        grecaptcha.reset();
                    }
                }
            });
            return false;
        }
    })
});
