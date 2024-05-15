<?php
include("database.php");

// Handle form submission for adding/editing Bebidas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $negocioId = filter_input(INPUT_POST, "negocioId", FILTER_SANITIZE_NUMBER_INT);
    $nombreBebida = filter_input(INPUT_POST, "nombreBebida", FILTER_SANITIZE_SPECIAL_CHARS);
    $descripcion = filter_input(INPUT_POST, "descripcion", FILTER_SANITIZE_SPECIAL_CHARS);
    $bebidasId = filter_input(INPUT_POST, "bebidasId", FILTER_SANITIZE_NUMBER_INT);

    if (empty($nombreBebida) || empty($descripcion) || $negocioId === null) {
        echo "Please fill all the fields.";
    } else {
        if (empty($bebidasId)) {
            // Add new Bebidas
            $sql = "INSERT INTO Bebidas (NegocioId, NombreBebida, Descripcion) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $negocioId, $nombreBebida, $descripcion);
        } else {
            // Update existing Bebidas
            $sql = "UPDATE Bebidas SET NegocioId = ?, NombreBebida = ?, Descripcion = ? WHERE BebidasId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issi", $negocioId, $nombreBebida, $descripcion, $bebidasId);
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
$bebidas = null;
if (isset($_GET['edit'])) {
    $bebidasId = $_GET['edit'];
    $sql = "SELECT * FROM Bebidas WHERE BebidasId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bebidasId);
    $stmt->execute();
    $result = $stmt->get_result();
    $bebidas = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bebidas</title>
    <!-- Bootstrap CSS -->
    <link href="styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Bebidas</h2>
    <div class="row mt-3">
        <div class="col">
            <?php
            // Check if the database name is provided as a query parameter
            if (isset($_GET['database'])) {
                // SQL query to retrieve Bebidas data
                $sql = "SELECT * FROM Bebidas";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    echo "<table class='table'>";
                    echo "<thead><tr><th>Bebidas ID</th><th>Negocio ID</th><th>Nombre Bebida</th><th>Descripcion</th><th>Actions</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row["BebidasId"] . "</td><td>" . $row["NegocioId"] . "</td><td>" . $row["NombreBebida"] . "</td><td>" . $row["Descripcion"] . "</td>";
                        echo "<td><a href='?edit=" . $row["BebidasId"] . "#form'>Edit</a></td></tr>";
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
            ?>
        </div>
    </div>

    <h2 id="form"><?php echo isset($bebidas) ? "Edit Bebidas" : "Add New Bebidas"; ?></h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="bebidasId" value="<?php echo isset($bebidas) ? $bebidas['BebidasId'] : ''; ?>">
        Negocio ID:<br>
        <input type="text" name="negocioId" value="<?php echo isset($bebidas) ? $bebidas['NegocioId'] : ''; ?>"><br>
        Nombre Bebida:<br>
        <input type="text" name="nombreBebida" value="<?php echo isset($bebidas) ? $bebidas['NombreBebida'] : ''; ?>"><br>
        Descripcion:<br>
        <input type="text" name="descripcion" value="<?php echo isset($bebidas) ? $bebidas['Descripcion'] : ''; ?>"><br>
        <input type="submit" value="<?php echo isset($bebidas) ? "Update" : "Add"; ?>">
    </form>
</div>

</body>
</html>
