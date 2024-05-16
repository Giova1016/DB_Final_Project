<?php
include("database.php");

// Handle form submission for adding/editing Negocio
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $duenoId = filter_input(INPUT_POST, "duenoId", FILTER_SANITIZE_NUMBER_INT);
    $nombreNegocio = filter_input(INPUT_POST, "nombreNegocio", FILTER_SANITIZE_SPECIAL_CHARS);
    $menuBebidas = filter_input(INPUT_POST, "menuBebidas", FILTER_SANITIZE_SPECIAL_CHARS);
    $negocioId = filter_input(INPUT_POST, "negocioId", FILTER_SANITIZE_NUMBER_INT);

    if (empty($nombreNegocio) || empty($duenoId)) {
        echo "Please fill all the fields.";
    } else {
        if (empty($negocioId)) {
            // Add new Negocio
            $sql = "INSERT INTO Negocio (DueñoId, NombreNegocio, MenuBebidas) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $duenoId, $nombreNegocio, $menuBebidas);
        } else {
            // Update existing Negocio
            $sql = "UPDATE Negocio SET DueñoId = ?, NombreNegocio = ?, MenuBebidas = ? WHERE NegocioId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issi", $duenoId, $nombreNegocio, $menuBebidas, $negocioId);
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
$negocio = null;
if (isset($_GET['edit'])) {
    $negocioId = $_GET['edit'];
    $sql = "SELECT * FROM Negocio WHERE NegocioId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $negocioId);
    $stmt->execute();
    $result = $stmt->get_result();
    $negocio = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Data</title>
    <!-- Bootstrap CSS -->
    <link href="styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Negocios</h2>
    <div class="row mt-3">
        <div class="col">
            <?php
            // Check if the database name is provided as a query parameter
            if (isset($_GET['database'])) {
                // SQL query to retrieve Negocio data
                $sql = "SELECT * FROM Negocio";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    echo "<table class='table'>";
                    echo "<thead><tr><th>Negocio ID</th><th>Dueño ID</th><th>Nombre Negocio</th><th>Menu Bebidas</th><th>Actions</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row["NegocioId"] . "</td><td>" . $row["DueñoId"] . "</td><td>" . $row["NombreNegocio"] . "</td><td>" . $row["MenuBebidas"] . "</td>";
                        echo "<td><a href='?edit=" . $row["NegocioId"] . "#form'>Edit</a></td></tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "0 results";
                }

                // Close connection
                
            } else {
                echo "No database selected.";
            }
            
            ?>
        </div>
        </div>
    
     <h2 id="form"><?php echo isset($negocio) ? "Edit Negocio" : "Add New Negocio"; ?></h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="negocioId" value="<?php echo isset($negocio) ? $negocio['NegocioId'] : ''; ?>">
        Dueño ID:<br>
        <input type="text" name="duenoId" value="<?php echo isset($negocio) ? $negocio['DueñoId'] : ''; ?>" class="form-control"><br>
        Nombre Negocio:<br>
        <input type="text" name="nombreNegocio" value="<?php echo isset($negocio) ? $negocio['NombreNegocio'] : ''; ?>" class="form-control"><br>
        Menu Bebidas:<br>
        <input type="text" name="menuBebidas" value="<?php echo isset($negocio) ? $negocio['MenuBebidas'] : ''; ?>" class="form-control"><br>
        <input type="submit" value="<?php echo isset($cliente) ? "Update" : "Add"; ?>" class="btn btn-primary mt-2">        
    </form>
</div>

</body>
</html>