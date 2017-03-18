//This code handles the view order status change for the form

$(document).ready(function() {
            $("#status").on('change', function() {
                $("#statusform").submit();
            });
        });