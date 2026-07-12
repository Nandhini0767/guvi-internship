$(document).ready(function () {

    $("#registerForm").submit(function (e) {

        e.preventDefault();

        // Show loader
        $("#loader").show();

        // Disable Register button
        $("button[type='submit']").prop("disabled", true);

        $.ajax({

            url: "php/register.php",
            type: "POST",

            data: {
                name: $("#name").val(),
                email: $("#email").val(),
                password: $("#password").val()
            },

            success: function (response) {

                // Hide loader
                $("#loader").hide();

                // Enable Register button
                $("button[type='submit']").prop("disabled", false);

                alert(response);

                // Clear the form
                $("#registerForm")[0].reset();

                // Redirect to Login page after successful registration
                if (response.includes("Registration Successful")) {

                    setTimeout(function () {
                        window.location.href = "login.html";
                    }, 1000);

                }

            },

            error: function () {

                // Hide loader
                $("#loader").hide();

                // Enable Register button
                $("button[type='submit']").prop("disabled", false);

                alert("Server Error!");

            }

        });

    });

});