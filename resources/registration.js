$(document).ready(function() {
    $('#status').hide();
    $('#success').hide();
    $('#unwarning').hide();
    $('#pwarning').hide();
    $('#cpwarning').hide();
    $('.nullsubmitbtn').prop('disabled', true);

    $('#un').on("input", function() {
        var value = $('#un').val();
        if (value.length > 8) {
            $('#unwarning').show();
            $('#registrationSubmitBtn').attr('submitbtn', 'nullsubmitbtn');
            $('#registrationSubmitBtn').prop('disabled', true);
        } else {
            $('#unwarning').hide();
            if ($("#pw").val() !== "" && $("#pw").val() === $("#cpw").val() && checkPasswordWithCommonPasswords($("#pw").val())) {
                $("#registrationSubmitBtn").attr('nullsubmitbtn', 'submitbtn');
                $("#registrationSubmitBtn").prop('disabled', false);
            }
        }
    });
    $('#pw').on("input", function() {
        var value = $("#pw").val();
        if (!checkPasswordWithCommonPasswords(value)) {
            $('#pwarning').show();
            $('#registrationSubmitBtn').attr('submitbtn', 'nullsubmitbtn');
            $('#registrationSubmitBtn').prop('disabled', true);
        } else {
            $('#pwarning').hide();
            if (value === $("cpw").val() && value !== "" && $('#un').val().length < 9) {
                $("#registrationSubmitBtn").attr('nullsubmitbtn', 'submitbtn');
                $("#registrationSubmitBtn").prop('disabled', false);
            }
        }
    });
    $("#cpw").on("input", function() {
        var value = $("#cpw").val();
        if (value !== $("#pw").val()) {
            $('#cpwarning').show();
            $('#registrationSubmitBtn').attr('submitbtn', 'nullsubmitbtn');
            $('#registrationSubmitBtn').prop('disabled', true);
        } else {
            $("#cpwarning").hide();
            if (checkPasswordWithCommonPasswords(value) && value !== "" && $("#un").val().length < 9) {
                $("#registrationSubmitBtn").attr('nullsubmitbtn', 'submitbtn');
                $("#registrationSubmitBtn").prop('disabled', false);
            }
        }
    });
    $('#registrationSubmitBtn').click(function () {
        var username = $('#un').val();
        var pass = $('#pw').val();
        var cpass = $('#cpw').val();
        if (pass !== cpass) {
            $("#status").show();
            $("#status").text("Password does not equal confirmation!");
            return;
        }
        $.post("php/action_register.php", {
            un: username,
            pw: pass
        }, function(data, status) {
            if (data.endsWith("success")) {
                $("#register").hide();
                $("#success").show();
            } else {
                $("#status").show();
                $("#status").html(data);
            }
        });
    });
});

function checkPassword(inputtxt) {
    var decimal=  /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/;
    if (inputtxt.value.match(decimal)) {
        return true;
    }
    console.log("Password failed validation check!");
    return false;
}

var mostCommonPasswordsList = "";

function checkPasswordWithCommonPasswords(inputpass) {
    if (!mostCommonPasswordsList.startsWith("123456")) {
        $.get('resources/most-common-passwords.txt', function(data, status) {
            mostCommonPasswordsList = data;
        })
    }
    console.log(mostCommonPasswordsList);
    if (!mostCommonPasswordsList.startsWith("123456")) {
        console.log("Impossible!");
        return false;
    }
    var thePasswords = mostCommonPasswordsList.split('\n');
    for (var i = 0; i < thePasswords.length; i++) {
        if (inputpass == thePasswords[i]) {
            console.log("Password is on no-no list!");
            return false;
        }
    }
    return true;
}