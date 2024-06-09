<?php
session_start(); // Start the session if not already started

if (isset($_SESSION['session_id'])) {
    echo "Your Session ID: " . $_SESSION['session_id'];
} else {
    echo "Session ID not found."; // Handle this case if needed
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>District 4 Laguna Selections</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="script.js"></script> </head>
<body>
    <h1>District 4 Laguna Location</h1>
    <select id="municipalities">
        <option value="">Select Municipality</option>
    </select>

    <select id="barangays">
        <option value="">Select Barangay</option>
    </select>
</body>
</html>


<script>

$(document).ready(function() {
    // Load municipalities on page load
    loadMunicipalities();

    // When the municipality selection changes:
    $('#municipalities').on('change', function() {
        var municipality = $(this).val();
        if (municipality) {
            loadBarangays(municipality);
        } else {
            $('#barangays').empty().append('<option value="">Select Barangay</option>');
        }
    });
});

function loadMunicipalities() {
    $.ajax({
        url: 'get_municipalities.php', 
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#municipalities').append($('<option>', {value: '', text: 'Select Municipality'}));
            $.each(data, function(index, name) {
                $('#municipalities').append($('<option>', {value: name, text: name}));
            });
        }
    });
}

function loadBarangays(municipality) {
    $.ajax({
        url: 'get_barangays.php',
        type: 'GET',
        dataType: 'json',
        data: { municipality: municipality },
        success: function(data) {
            $('#barangays').empty().append('<option value="">Select Barangay</option>');
            $.each(data, function(index, name) {
                $('#barangays').append($('<option>', {value: name, text: name}));
            });
        }
    });
}


    </script>