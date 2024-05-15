<?php
include("database.php");

// Handle form submission for adding/editing Administrador
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuarioId = filter_input(INPUT_POST, "usuarioId", FILTER_SANITIZE_NUMBER_INT);
    $adminId = filter_input(INPUT_POST, "adminId", FILTER_SANITIZE_NUMBER_INT);

    if (empty($usuarioId)) {
        echo "Please fill all the fields.";
    } else {
        if (empty($adminId)) {
            // Add new Administrador
            $sql = "INSERT INTO Administrador (UsuarioId) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $usuarioId);
        } else {
            // Update existing Administrador
            $sql = "UPDATE Administrador SET UsuarioId = ? WHERE AdminId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $usuarioId, $adminId);
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
$administrador = null;
if (isset($_GET['edit'])) {
    $adminId = $_GET['edit'];
    $sql = "SELECT * FROM Administrador WHERE AdminId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    $administrador = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Administradores</h2>
    <div class="row mt-3">
        <div class="col">
            <?php
            // SQL query to retrieve Administrador data
            $sql = "SELECT * FROM Administrador";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table class='table'>";
                echo "<thead><tr><th>Admin ID</th><th>Usuario ID</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["AdminId"] . "</td><td>" . $row["UsuarioId"] . "</td>";
                    echo "<td><a href='?edit=" . $row["AdminId"] . "#form'>Edit</a></td></tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "0 results";
            }
            ?>
        </div>
    </div>

    <h2 id="form"><?php echo isset($administrador) ? "Edit Administrador" : "Add New Administrador"; ?></h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="adminId" value="<?php echo isset($administrador) ? $administrador['AdminId'] : ''; ?>">
        Usuario ID:<br>
        <input type="text" name="usuarioId" value="<?php echo isset($administrador) ? $administrador['UsuarioId'] : ''; ?>" class="form-control"><br>
        <input type="submit" value="<?php echo isset($administrador) ? "Update" : "Add"; ?>" class="btn btn-primary mt-2">
    </form>
</div>

</body>
</html>
