<?php
include("database.php");

// Handle form submission for adding/editing Inventario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $negocioId = filter_input(INPUT_POST, "negocioId", FILTER_SANITIZE_NUMBER_INT);
    $descripcion = filter_input(INPUT_POST, "descripcion", FILTER_SANITIZE_SPECIAL_CHARS);
    $cantidad = filter_input(INPUT_POST, "cantidad", FILTER_SANITIZE_NUMBER_INT);
    $inventoryId = filter_input(INPUT_POST, "inventoryId", FILTER_SANITIZE_NUMBER_INT);

    if (empty($negocioId) || empty($descripcion) || empty($cantidad)) {
        echo "Please fill all the fields.";
    } else {
        if (empty($inventoryId)) {
            // Add new Inventario
            $sql = "INSERT INTO Inventario (NegocioId, Descripcion, Cantidad) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isi", $negocioId, $descripcion, $cantidad);
        } else {
            // Update existing Inventario
            $sql = "UPDATE Inventario SET NegocioId = ?, Descripcion = ?, Cantidad = ? WHERE InventoryId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isii", $negocioId, $descripcion, $cantidad, $inventoryId);
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
$inventario = null;
if (isset($_GET['edit'])) {
    $inventoryId = $_GET['edit'];
    $sql = "SELECT * FROM Inventario WHERE InventoryId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $inventoryId);
    $stmt->execute();
    $result = $stmt->get_result();
    $inventario = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Inventario</h2>
    <div class="row mt-3">
        <div class="col">
            <?php
            // SQL query to retrieve Inventario data
            $sql = "SELECT * FROM Inventario";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table class='table'>";
                echo "<thead><tr><th>Inventory ID</th><th>Negocio ID</th><th>Descripcion</th><th>Cantidad</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["InventoryId"] . "</td><td>" . $row["NegocioId"] . "</td><td>" . $row["Descripcion"] . "</td><td>" . $row["Cantidad"] . "</td>";
                    echo "<td><a href='?edit=" . $row["InventoryId"] . "#form'>Edit</a></td></tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "0 results";
            }
            ?>
        </div>
    </div>

    <h2 id="form"><?php echo isset($inventario) ? "Edit Inventario" : "Add New Inventario"; ?></h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="inventoryId" value="<?php echo isset($inventario) ? $inventario['InventoryId'] : ''; ?>">
        Negocio ID:<br>
        <input type="text" name="negocioId" value="<?php echo isset($inventario) ? $inventario['NegocioId'] : ''; ?>" class="form-control"><br>
        Descripcion:<br>
        <input type="text" name="descripcion" value="<?php echo isset($inventario) ? $inventario['Descripcion'] : ''; ?>" class="form-control"><br>
        Cantidad:<br>
        <input type="text" name="cantidad" value="<?php echo isset($inventario) ? $inventario['Cantidad'] : ''; ?>" class="form-control"><br>
        <input type="submit" value="<?php echo isset($inventario) ? "Update" : "Add"; ?>" class="btn btn-primary mt-2">
    </form>
</div>

</body>
</html>
