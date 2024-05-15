<?php
include("database.php");

// Handle form submission for adding/editing Sirve
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bebidaId = filter_input(INPUT_POST, "bebidaId", FILTER_SANITIZE_NUMBER_INT);
    $negocioId = filter_input(INPUT_POST, "negocioId", FILTER_SANITIZE_NUMBER_INT);

    if (empty($bebidaId) || empty($negocioId)) {
        echo "Please fill all the fields.";
    } else {
        // Add new Sirve
        $sql = "INSERT INTO Sirve (BebidaId, NegocioId) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $bebidaId, $negocioId);

        if ($stmt->execute()) {
            echo "Record successfully saved.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Prepare for editing
$sirve = null;
if (isset($_GET['edit'])) {
    $sirveId = $_GET['edit'];
    $sql = "SELECT * FROM Sirve WHERE SirveId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $sirveId);
    $stmt->execute();
    $result = $stmt->get_result();
    $sirve = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Sirve</title>
    <link href="styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Sirve</h2>
    <div class="row mt-3">
        <div class="col">
            <?php
            // SQL query to retrieve Sirve data
            $sql = "SELECT * FROM Sirve";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table class='table'>";
                echo "<thead><tr><th>Bebida ID</th><th>Negocio ID</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["BebidaId"] . "</td><td>" . $row["NegocioId"] . "</td>";
                    echo "<td><a href='?edit=" . $row["SirveId"] . "#form'>Edit</a></td></tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "0 results";
            }
            ?>
        </div>
    </div>

    <h2 id="form"><?php echo isset($sirve) ? "Edit Sirve" : "Add New Sirve"; ?></h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Bebida ID:<br>
        <input type="text" name="bebidaId" value="<?php echo isset($sirve) ? $sirve['BebidaId'] : ''; ?>" class="form-control"><br>
        Negocio ID:<br>
        <input type="text" name="negocioId" value="<?php echo isset($sirve) ? $sirve['NegocioId'] : ''; ?>" class="form-control"><br>
        <input type="submit" value="<?php echo isset($sirve) ? "Update" : "Add"; ?>" class="btn btn-primary mt-2">
    </form>
</div>

</body>
</html>
