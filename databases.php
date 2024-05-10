<?php
// Database connection details
$servername = "localhost";
$username = "username";
$password = "password";
$db_name = "databasename";

// Create connection
$conn = new mysqli($servername, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection falied: " .$conn->connect_error);
}

// Handle AJAX requests from JavaScript
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission for adding a new business
    if(isset($_POST['action']) && $_POST['action'] == 'addBusiness') {
        $businessName = $_POST['businessName'];
        $ownerId = $_POST['ownerId'];

        // Insert the new business into the database
        $sql = "INSERT INTO Negocio (DueñoId) VALUES ($ownerId)";
        if ($conn->query($sql) === TRUE) {
            $lastId = $conn->insert_id;
            echo "New business added with ID: " .$lastId;
        } else {
            echo "Error: " . $sql . "<br>" .$conn->error; 
        }
    }
}

// Handle AJAX requests for fetching data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch data from the database
    $sql = "SELECT * FROM Negocio";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data in a format that can be consumed by JavaScript
        $data = array();
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo "No data found";
    }
}

$conn->close();
?>