$(document).ready(function () {

    $("#loginForm").submit(function (e) {

        e.preventDefault();

        $.ajax({

            url: "php/login.php",
            type: "POST",
            dataType: "json",

            data: {
                email: $("#email").val(),
                password: $("#password").val()
            },

            success: function (res) {

                if (res.status === "success") {

                    // Store login details
                    localStorage.setItem("token", res.token);
                    localStorage.setItem("name", res.name);
                    localStorage.setItem("email", res.email);

                    alert("Login Successful!");

                    window.location.href = "profile.html";

                } else {

                    alert(res.message);

                }

            },

            error: function () {

                alert("Server Error!");

            }

        });

    });

});