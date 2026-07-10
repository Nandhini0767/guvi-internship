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

                // Clear the form
                $("#registerForm")[0].reset();

                // If registration was successful,
                // automatically go to the login page
                if (response.includes("Registration Successful")) {

                    setTimeout(function () {
                        window.location.href = "login.html";
                    }, 1000); // Redirect after 1 second

                }

            },

            error: function () {

                alert("Server Error!");

            }

        });

    });

});