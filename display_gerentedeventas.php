<?php
include("database.php");

// Handle form submission for adding/editing GerenteVentas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuarioId = filter_input(INPUT_POST, "usuarioId", FILTER_SANITIZE_NUMBER_INT);
    $gerenteId = filter_input(INPUT_POST, "gerenteId", FILTER_SANITIZE_NUMBER_INT);

    if (empty($usuarioId)) {
        echo "Please fill all the fields.";
    } else {
        if (empty($gerenteId)) {
            // Add new GerenteVentas
            $sql = "INSERT INTO `GerenteVentas` (UsuarioId) VALUES (?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Prepare statement failed: " . $conn->error);
            }
            $stmt->bind_param("i", $usuarioId);
        } else {
            // Update existing GerenteVentas
            $sql = "UPDATE `GerenteVentas` SET UsuarioId = ? WHERE GerenteId = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Prepare statement failed: " . $conn->error);
            }
            $stmt->bind_param("ii", $usuarioId, $gerenteId);
        }

        if ($stmt->execute()) {
            echo "Record successfully saved.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Prepare for editing
$gerente = null;
if (isset($_GET['edit'])) {
    $gerenteId = $_GET['edit'];
    $sql = "SELECT * FROM `GerenteVentas` WHERE GerenteId = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare statement failed: " . $conn->error);
    }
    $stmt->bind_param("i", $gerenteId);
    $stmt->execute();
    $result = $stmt->get_result();
    $gerente = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display GerenteVentas</title>
    <link href="styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>GerenteVentas</h2>
    <div class="row mt-3">
        <div class="col">
            <?php
            // SQL query to retrieve GerenteVentas data
            $sql = "SELECT * FROM `GerenteVentas`";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table class='table'>";
                echo "<thead><tr><th>Gerente ID</th><th>Usuario ID</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["GerenteId"] . "</td><td>" . $row["UsuarioId"] . "</td>";
                    echo "<td><a href='?edit=" . $row["GerenteId"] . "#form'>Edit</a></td></tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "0 results";
            }
            ?>
        </div>
    </div>

    <h2 id="form"><?php echo isset($gerente) ? "Edit GerenteVentas" : "Add New GerenteVentas"; ?></h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="gerenteId" value="<?php echo isset($gerente) ? $gerente['GerenteId'] : ''; ?>">
        Usuario ID:<br>
        <input type="text" name="usuarioId" value="<?php echo isset($gerente) ? $gerente['UsuarioId'] : ''; ?>" class="form-control"><br>
        <input type="submit" value="<?php echo isset($gerente) ? "Update" : "Add"; ?>" class="btn btn-primary mt-2">
    </form>
</div>

</body>
</html>
