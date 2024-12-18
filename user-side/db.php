<?php
    // Establish connection
    $conn = mysqli_connect("localhost", "root", "1234", "traveloca");

    // Check for connection errors
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>
