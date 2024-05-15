<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styles.css" rel="stylesheet">
    <title>Query Result</title>
</head>
<body>
    <h2>Query Result:</h2>
    <div class="container mt-5">
    <?php
    // Check if the query is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the query from the form
        $query = $_POST["query"];

        // Check if query contains restricted keywords
        if (stripos($query, "password") !== false || stripos($query, "*") !== false) {
            echo "You do not have permission to execute this query.";
        } else {
            // Execute the query
            include("database.php"); // Include your database connection file
            $result = $conn->query($query);

            if ($result) {
                // Check if there are results
                if ($result->num_rows > 0) {
                    // Output data of each row
                    echo "<table border='1'>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($row as $value) {
                            echo "<td>" . $value . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "0 results";
                }
            } else {
                echo "Error executing query: " . $conn->error;
            }

            // Close connection
            $conn->close();
        }
    } else {
        echo "No query submitted.";
    }
    ?>
</div>
</body>
</html>