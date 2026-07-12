$(document).ready(function () {

    let token = localStorage.getItem("token");

    if (!token) {

        alert("Please login first");

        window.location.href = "login.html";

        return;

    }

    // Show loader while loading profile
    $("#loader").show();

    // Load Profile
    $.ajax({

        url: "php/profile.php",

        type: "POST",

        dataType: "json",

        data: {

            token: token

        },

        success: function (res) {

            $("#loader").hide();

            if (res.status === "success") {

                $("#email").val(res.email);
                $("#name").val(res.name);
                $("#age").val(res.age);
                $("#dob").val(res.dob);
                $("#contact").val(res.contact);

            } else {

                alert(res.message);

                window.location.href = "login.html";

            }

        },

        error: function (xhr) {

            $("#loader").hide();

            console.log(xhr.responseText);

            alert("Error loading profile.");

        }

    });

    // Save Profile
    $("#profileForm").submit(function (e) {

        e.preventDefault();

        $("#loader").show();

        $.ajax({

            url: "php/profile.php",

            type: "POST",

            dataType: "json",

            data: {

                token: token,

                name: $("#name").val(),

                age: $("#age").val(),

                dob: $("#dob").val(),

                contact: $("#contact").val()

            },

            success: function (res) {

                $("#loader").hide();

                if (res.status === "success") {

                    alert("Profile updated successfully!");

                } else {

                    alert(res.message);

                }

            },

            error: function (xhr) {

                $("#loader").hide();

                console.log(xhr.responseText);

                alert("Server Error!");

            }

        });

    });

    // Logout
    $("#logoutBtn").click(function () {

        $("#loader").show();

        $.ajax({

            url: "php/logout.php",

            type: "POST",

            dataType: "json",

            data: {

                token: token

            },

            complete: function () {

                localStorage.removeItem("token");

                window.location.href = "login.html";

            }

        });

    });

});