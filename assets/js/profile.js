$(document).ready(function () {

    console.log("Profile JS Loaded");

    let token = localStorage.getItem("token");

    if (!token) {
        alert("Please login first");
        window.location.href = "login.html";
        return;
    }

    // Automatically display logged-in email
    $("#email").val(localStorage.getItem("email"));

    // Load profile
    $.ajax({
        url: "php/profile.php",
        type: "POST",
        dataType: "json",
        data: {
            token: token
        },
        success: function (res) {

            if (res.status === "success") {

                $("#name").val(res.name);
                $("#age").val(res.age);
                $("#dob").val(res.dob);
                $("#contact").val(res.contact);

                // Keep email after refresh
                $("#email").val(localStorage.getItem("email"));

            } else {

                alert(res.message);

            }

        },
        error: function (xhr) {

            console.log(xhr.responseText);
            alert("Error loading profile");

        }
    });

    // Save profile
    $("#profileForm").submit(function (e) {

        e.preventDefault();

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

                if (res.status === "success") {

                    alert("Profile updated successfully!");

                } else {

                    alert(res.message);

                }

            },
            error: function (xhr) {

                console.log(xhr.responseText);
                alert("Server Error!");

            }

        });

    });

});