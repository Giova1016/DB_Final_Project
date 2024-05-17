<?php
include("database.php");

// Handle form submission for adding/editing Promociones
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $negocioId = filter_input(INPUT_POST, "negocioId", FILTER_SANITIZE_NUMBER_INT);
    $bebidasId = filter_input(INPUT_POST, "bebidasId", FILTER_SANITIZE_NUMBER_INT);
    $descuento = filter_input(INPUT_POST, "descuento", FILTER_SANITIZE_SPECIAL_CHARS);
    $promocionesId = filter_input(INPUT_POST, "promocionesId", FILTER_SANITIZE_NUMBER_INT);

    if (empty($negocioId) || empty($bebidasId) || empty($descuento)) {
        echo "Please fill all the fields.";
    } else {
        if (empty($promocionesId)) {
            // Add new Promocion
            $sql = "INSERT INTO Promociones (NegocioId, BebidasId, Descuento) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $negocioId, $bebidasId, $descuento);
        } else {
            // Update existing Promocion
            $sql = "UPDATE Promociones SET NegocioId = ?, BebidasId = ?, Descuento = ? WHERE PromocionesId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisi", $negocioId, $bebidasId, $descuento, $promocionesId);
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
$promocion = null;
if (isset($_GET['edit'])) {
    $promocionesId = $_GET['edit'];
    $sql = "SELECT * FROM Promociones WHERE PromocionesId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $promocionesId);
    $stmt->execute();
    $result = $stmt->get_result();
    $promocion = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Promociones</title>
    <link href="styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Promociones</h2>
    <div class="row mt-3">
        <div class="col">
            <?php
            // SQL query to retrieve Promociones data
            $sql = "SELECT * FROM Promociones";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table class='table'>";
                echo "<thead><tr><th>Promociones ID</th><th>Negocio ID</th><th>Bebidas ID</th><th>Descuento</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["PromocionesId"] . "</td><td>" . $row["NegocioId"] . "</td><td>" . $row["BebidasId"] . "</td><td>" . $row["Descuento"] . "</td>";
                    echo "<td><a href='?edit=" . $row["PromocionesId"] . "#form'>Edit</a></td></tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "0 results";
            }
            ?>
        </div>
    </div>

    <h2 id="form"><?php echo isset($promocion) ? "Edit Promocion" : "Add New Promocion"; ?></h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="promocionesId" value="<?php echo isset($promocion) ? $promocion['PromocionesId'] : ''; ?>">
        Negocio ID:<br>
        <input type="text" name="negocioId" value="<?php echo isset($promocion) ? $promocion['NegocioId'] : ''; ?>" class="form-control"><br>
        Bebidas ID:<br>
        <input type="text" name="bebidasId" value="<?php echo isset($promocion) ? $promocion['BebidasId'] : ''; ?>" class="form-control"><br>
        Descuento:<br>
        <input type="text" name="descuento" value="<?php echo isset($promocion) ? $promocion['Descuento'] : ''; ?>" class="form-control"><br>
        <input type="submit" value="<?php echo isset($promocion) ? "Update" : "Add"; ?>" class="btn btn-primary mt-2">
    </form>
</div>

</body>
</html>
