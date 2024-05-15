<?php
include("database.php");

// Handle form submission for adding/editing Sabores
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bebidaId = filter_input(INPUT_POST, "bebidaId", FILTER_SANITIZE_NUMBER_INT);
    $tiposSabores = filter_input(INPUT_POST, "tiposSabores", FILTER_SANITIZE_SPECIAL_CHARS);
    $saborId = filter_input(INPUT_POST, "saborId", FILTER_SANITIZE_NUMBER_INT);

    if (empty($bebidaId) || empty($tiposSabores)) {
        echo "Please fill all the fields.";
    } else {
        if (empty($saborId)) {
            // Add new Sabor
            $sql = "INSERT INTO Sabores (BebidaId, TiposSabores) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $bebidaId, $tiposSabores);
        } else {
            // Update existing Sabor
            $sql = "UPDATE Sabores SET BebidaId = ?, TiposSabores = ? WHERE SaborId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isi", $bebidaId, $tiposSabores, $saborId);
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
$sabor = null;
if (isset($_GET['edit'])) {
    $saborId = $_GET['edit'];
    $sql = "SELECT * FROM Sabores WHERE SaborId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $saborId);
    $stmt->execute();
    $result = $stmt->get_result();
    $sabor = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Sabores</title>
    <link href="styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Sabores</h2>
    <div class="row mt-3">
        <div class="col">
            <?php
            // SQL query to retrieve Sabores data
            $sql = "SELECT * FROM Sabores";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table class='table'>";
                echo "<thead><tr><th>Sabor ID</th><th>Bebida ID</th><th>Tipos Sabores</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["SaborId"] . "</td><td>" . $row["BebidaId"] . "</td><td>" . $row["TiposSabores"] . "</td>";
                    echo "<td><a href='?edit=" . $row["SaborId"] . "#form'>Edit</a></td></tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "0 results";
            }
            ?>
        </div>
    </div>

    <h2 id="form"><?php echo isset($sabor) ? "Edit Sabor" : "Add New Sabor"; ?></h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="saborId" value="<?php echo isset($sabor) ? $sabor['SaborId'] : ''; ?>">
        Bebida ID:<br>
        <input type="text" name="bebidaId" value="<?php echo isset($sabor) ? $sabor['BebidaId'] : ''; ?>" class="form-control"><br>
        Tipos Sabores:<br>
        <input type="text" name="tiposSabores" value="<?php echo isset($sabor) ? $sabor['TiposSabores'] : ''; ?>" class="form-control"><br>
        <input type="submit" value="<?php echo isset($sabor) ? "Update" : "Add"; ?>" class="btn btn-primary mt-2">
    </form>
</div>

</body>
</html>
