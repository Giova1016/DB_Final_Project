<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Users</h2>
    <div class="row mt-3">
        <div class="col">
            <?php
            // Check if the database name is provided as a query parameter
            if(isset($_GET['database'])) {
                // Retrieve the selected database name from the query parameter
                $database = $_GET['database'];

                include("database.php");

                // SQL query to retrieve sample data
                $sql = "SELECT * FROM usuario";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    echo "<table class='table'>";
                    echo "<thead><tr><th>Name</th><th>BirthDay</th><th>Direction</th><th>Email</th></tr></thead>";
                    echo "<tbody>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td>".$row["NombreC"]."</td><td>".$row["FechaNac"]."</td><td>".$row["Direccion"]."</td><td>".$row["Email"]."</td></tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "0 results";
                }

                // Close connection
                $conn->close();
            } else {
                echo "No database selected.";
            }

            include("insert_user.php");
            ?>
        </div>
    </div>
</div>

</body>
</html>
