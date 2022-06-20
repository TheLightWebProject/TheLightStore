$(document).ready(function () {
    $("#btnRegister").click(function () {
        $.post($("#user_form").attr("action"), $("#user_form").serialize(),
            function () {
                alert('Form 1 submitted');
            });

        $.post($("#customer_form").attr("action"), $("#user_form").serialize(),
            function () {
                alert('Form 2 submitted');
            });
    });
});