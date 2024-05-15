<?php
include("database.php");

// Handle form submission for adding/editing DueñoNegocio
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuarioId = filter_input(INPUT_POST, "usuarioId", FILTER_SANITIZE_NUMBER_INT);
    $duenoId = filter_input(INPUT_POST, "duenoId", FILTER_SANITIZE_NUMBER_INT);

    if (empty($usuarioId)) {
        echo "Please fill all the fields.";
    } else {
        if (empty($duenoId)) {
            // Add new DueñoNegocio
            $sql = "INSERT INTO DueñoNegocio (UsuarioId) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $usuarioId);
        } else {
            // Update existing DueñoNegocio
            $sql = "UPDATE DueñoNegocio SET UsuarioId = ? WHERE DueñoId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $usuarioId, $duenoId);
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
$dueno = null;
if (isset($_GET['edit'])) {
    $duenoId = $_GET['edit'];
    $sql = "SELECT * FROM DueñoNegocio WHERE DueñoId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $duenoId);
    $stmt->execute();
    $result = $stmt->get_result();
    $dueno = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display DueñoNegocio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>DueñoNegocios</h2>
    <div class="row mt-3">
        <div class="col">
            <?php
            // SQL query to retrieve DueñoNegocio data
            $sql = "SELECT * FROM DueñoNegocio";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table class='table'>";
                echo "<thead><tr><th>Dueño ID</th><th>Usuario ID</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["DueñoId"] . "</td><td>" . $row["UsuarioId"] . "</td>";
                    echo "<td><a href='?edit=" . $row["DueñoId"] . "#form'>Edit</a></td></tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "0 results";
            }
            ?>
        </div>
    </div>

    <h2 id="form"><?php echo isset($dueno) ? "Edit DueñoNegocio" : "Add New DueñoNegocio"; ?></h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="duenoId" value="<?php echo isset($dueno) ? $dueno['DueñoId'] : ''; ?>">
        Usuario ID:<br>
        <input type="text" name="usuarioId" value="<?php echo isset($dueno) ? $dueno['UsuarioId'] : ''; ?>" class="form-control"><br>
        <input type="submit" value="<?php echo isset($dueno) ? "Update" : "Add"; ?>" class="btn btn-primary mt-2">
    </form>
</div>

</body>
</html>
