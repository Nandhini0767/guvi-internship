$(document).ready(function () {

    $("#registerForm").submit(function (e) {

        e.preventDefault();

        $.ajax({

            url: "php/register.php",
            type: "POST",

            data: {
                name: $("#name").val(),
                email: $("#email").val(),
                password: $("#password").val()
            },

            success: function (response) {

                alert(response);

                $("#registerForm")[0].reset();

            }

        });

    });

});