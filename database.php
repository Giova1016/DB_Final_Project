<?php
    $server_name = "localhost:3306"; // or the IP address/hostname of your MySQL server
    $username = "giovannirf"; // Replace with your username
    $password = "password"; //replace with your password
    // $db_name = "myDB"; // Replace with the name of the database being used

    // Create Connection
    $conn = new mysqli($server_name, $username, $password);

    // Check Connection
    if ($conn->connect_error) {
        die("Connection failed: " . mysqli_connect_error());
      }
    echo "Connected Successfully";
?>