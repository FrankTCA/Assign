$(document).ready(function() {
    $('#status').hide();
    $('.nullsubmitbtn').prop('disabled', true);

    $("#un").on("input", function() {
        var value = $("#un").val();
        if (value !== "" && $("#pw").val() !== "") {
            $("#loginSubmitBtn").attr('nullsubmitbtn', 'submitbtn');
            $("#loginSubmitBtn").prop('disabled', false);
        } else {
            $("#loginSubmitBtn").attr('submitbtn', 'nullsubmitbtn');
            $("#loginSubmitBtn").prop('disabled', true);
        }
    });

    $("#pw").on("input", function() {
        var value = $("#pw").val();
        if (value !== "" && $("#un").val() !== "") {
            $("#loginSubmitBtn").attr('nullsubmitbtn', 'submitbtn');
            $("#loginSubmitBtn").prop('disabled', false);
        } else {
            $("#loginSubmitBtn").attr('submitbtn', 'nullsubmitbtn');
            $("#loginSubmitBtn").prop('disabled', true);
        }
    });

    $("#loginSubmitBtn").click(function() {
        var username = $("#un").val();
        var password = $("#pw").val();

        if (username === "" || password === "") {
            return;
        }
        $.post("php/action_login.php", {
            un: username,
            pw: password
        }, function (data, status) {
            if (data.endsWith("success")) {
                window.location.href = "dash.php";
            } else {
                $("#status").show();
                $("#status").html(data);
            }
        });
    });
});

function onSubmit() {
    console.log("Submit button clicked!");
}