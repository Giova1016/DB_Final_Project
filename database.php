<?php
    $server_name = "localhost"; // or the IP address/hostname of your MySQL server
    $username = "root"; // Replace with your username
    $password = ""; //replace with your password
    $db_name = "cocktailbuddydb"; // Replace with the name of the database being used

    // Create Connection
    $conn = mysqli_connect($server_name, $username, $password, $db_name);

    // Check Connection
    if ($conn->connect_error) {
        die("Connection failed: " . mysqli_connect_error());
      }
?>