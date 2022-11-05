$(document).ready(function() {
    $('.invalidWarning').hide();
    $('#status').hide();

    $('#getLinkBtn').click(function() {
        $.get("php/action_getlink.php", function(data, status) {
            $('#link').html(data);
        });
    });

    $('#evtSubmitBtn').click(function() {
        let name = $('#evtNameBox').val();
        let description = $('#descrBox').val();
        let date = $('#dateBox').val();

        var data;

        if (name === "" || date === "") {
            $("#status").show();
            $("#status").html("Name and date boxes must be filled!");
            return;
        }

        if (!(/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/.test(date))) {
            $('#status').show();
            $('#status').html("Date must be written as MM/DD/YYYY");
            return;
        }

        if (description === "") {
            data = {
                name: name,
                date: date
            }
        } else {
            data = {
                name: name,
                description: description,
                date: date
            }
        }

        $.post("php/action_mkevt.php", data, function(data, status) {
            if (data.endsWith("success")) {
                $('#evtNameBox').val("");
                $('#descrBox').val("");
                $('#dateBox').val("");
                prompt("Event created successfully!");
            } else {
                $('#status').show();
                $('#status').html(data);
            }
        });
    });
});