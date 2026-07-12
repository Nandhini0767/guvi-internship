$(document).ready(function () {

    $("#loginForm").submit(function (e) {

        e.preventDefault();

        // Show loader
        $("#loader").show();

        // Disable Login button
        $("button[type='submit']").prop("disabled", true);

        $.ajax({

            url: "php/login.php",
            type: "POST",
            dataType: "json",

            data: {
                email: $("#email").val(),
                password: $("#password").val()
            },

            success: function (res) {

                // Hide loader
                $("#loader").hide();

                // Enable Login button
                $("button[type='submit']").prop("disabled", false);

                if (res.status === "success") {

                    // Store only the token
                    localStorage.setItem("token", res.token);

                    // Go directly to Profile page
                    window.location.href = "profile.html";

                } else {

                    alert(res.message);

                }

            },

            error: function () {

                // Hide loader
                $("#loader").hide();

                // Enable Login button
                $("button[type='submit']").prop("disabled", false);

                alert("Server Error!");

            }

        });

    });

});